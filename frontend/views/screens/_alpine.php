<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('screens', () => ({
            roomId: '<?= $roomId ?>',
            mode: '<?= $mode ?>',
            roomNumber: '<?= $roomNumber ?>',

            roomName: null,
            avatar: null,
            professionsText: null,
            appointment: null,
            appointments: null,

            footerText: null,
            roomStatuses: ['empty', 'free', 'wait', 'busy'],
            roomStatus: null,
            screens: ['no-schedule', 'room-info', 'invite'],
            screen: null,
            showInviteScreen: false,
            invitedAppId: null,
            inviteScreenTimeout: 5000,
            invite: {                                           // инфа по приглашению audio
                fullMessage: null,
                messageTop: null,
                messageBottom: null,
                audio: null
            },

            roomInfo: null,                                     // объект - инфа по комнате
            roomSequence: null,                                 // массив визитов
            busySequence: null,
            waitSequence: null,

            socketUrl: '<?= \Yii::$app->settings->getParam('socket_url') ?>',
            socketConn: null,

            // roomInfo: {
            //      name: 'Кабинет офтальмолога',
            //      avatar: 'https://files.rnova.org/198733bd446bb513a3bfe91ae1f3d391/2f3988fbcf0519ea27fdcefaf0d1772d.png',
            //      professionsText: 'Врач высшей категории',
            // },
            // roomSequence: [
            //     {
            //         id: 1111,
            //         status_id: 4,
            //         time_start: '15:00',
            //         patientNumber: '555',
            //         ticketCode: 'Л001',
            //     },
            //     {
            //         id: 2222,
            //         status_id: 3,
            //         time_start: '15:30',
            //         patientNumber: '444',
            //         ticketCode: 'Л002',
            //     },
            //     {
            //         id: 5555,
            //         status_id: 4,
            //         time_start: '16:00',
            //         patientNumber: '333',
            //         ticketCode: 'Л003',
            //     },
            // ],

            /**
             INIT
             * */
            initDefault() {
                this.setDefault();
                this.createSocketConnection();
            },
            setDefault() {
                this.getRoomInfo(() => {
                    this.getAppointments(() => {})
                })
            },

            /**
             SOCKET
             * */
            createSocketConnection() {
                let data = this;
                this.socketConn = new WebSocket(this.socketUrl);
                this.socketConn.onopen = function (e) {
                    data.register();
                };
                this.socketConn.onclose = function (e) {
                    data.closeConnection();
                    console.log('close')
                };
                this.socketConn.onerror = function (e) {
                    console.log('error')
                };
                this.socketConn.onmessage = function (e) {
                    data.handleMessage(e.data);
                };
            },
            closeConnection() {
                if (!this.socketConn) {
                    return;
                }
                this.socketConn.close();
            },
            register() {
                this.send('register', {roomId: this.roomId});
            },
            handleMessage(message) {
                let data = JSON.parse(message),
                    method = data['method'];
                if (!method) {
                    return false;
                }
                let app = this;
                this.setAppointment(data.data, (data) => {
                    switch (method) {
                        case 'register':
                            app.registerUser();
                            break;
                        case 'update':
                            app.handleUpdate();
                            break;
                        case 'notification':
                            app.handleNotification();
                            break;
                    }
                })

                return true;
            },
            send(method, data) {
                data['method'] = method;
                this.socketConn.send(JSON.stringify(data));
            },

            /**
             SOCKET WEBHOOK
             * */
            setAppointment(data, callback) {
                new Promise((resolve, reject) => {
                    this.appointment = data;
                    this.appointment.id = parseInt(data.id)
                    this.appointment.status_id = parseInt(data.status_id)
                    resolve(data);
                }).then((data) => {
                    callback(data);
                })
            },
            setAppointments(data, callback) {
                this.roomSequence = data;
                console.log('apps', this.roomSequence)
                this.setRoomData();
                callback();
            },
            setRoomData() {
                this.setSequences();
                this.setRoomScreen();
            },
            registerUser() {
                console.log('register')
                this.appointment = null;
            },
            handleAppointment() {
                let indexApp = null;
                let countSequence = this.roomSequence ? this.roomSequence.length : 0;
                let roomSequence = [];
                const appointment = this.getObject(this.appointment);

                if(this.roomSequence) {
                    this.roomSequence.forEach(function callback(currentValue, index, array) {
                        if(currentValue.id == appointment.id) {
                            indexApp = index
                        }
                    })
                    if(indexApp !== null) {
                        this.roomSequence[indexApp].status_id = appointment.status_id;
                    }
                    else {
                        this.roomSequence[countSequence] = appointment;
                    }
                }
                else {
                    roomSequence.push(appointment)
                    this.roomSequence = roomSequence;
                }

                this.prepareRoomSequence((data) => {
                    this.roomSequence = data
                    this.setRoomData();
                });
            },
            prepareRoomSequence(callback) {
                new Promise((resolve, reject) => {
                    let roomSequence = [];
                    this.roomSequence.forEach((item) => {
                        let obj = this.getObject(item);
                        if(obj.status_id == 2 || obj.status_id == 3) {
                            roomSequence.push(obj);
                        }
                    })
                    if(roomSequence.length) {
                        resolve(roomSequence);
                    }

                }).then((data) => {
                    callback(data);
                }).catch((e) => {
                    console.log('Ошибка prepareRoomSequence')
                })

                // callback()
            },
            handleUpdate() {
                this.handleAppointment();
                //this.setDefault();
            },
            handleNotification() {
                this.handleAppointment();
                //this.setDefault();
                this.inviteScreen();
            },

            /**
             API
             * */
            getAppointments(callback) {
                if(!this.roomInfo || !this.roomId) {
                    callback();
                    return;
                }
                const params = new URLSearchParams();
                params.set('roomId', this.roomId);
                params.set('doctorId', this.roomInfo.id);
                const response = this.loadData('/api/get-appointments', params)
                response.then((data) => {
                    this.setAppointments(data, () => {
                        callback();
                    })

                })
            },
            getObject(proxy) {
                if(!proxy) return;
                let obj = {};
                for (let key in proxy) {
                    obj[key] = proxy[key];
                }
                return obj;
            },
            getRoomInfo(callback) {
                if(this.roomInfo) {
                    callback();
                    return;
                }
                const params = new URLSearchParams();
                params.set('roomId', this.roomId);
                const response = this.loadData('/api/get-room', params)
                response.then((data) => {
                    this.roomInfo = data;
                    callback();
                })
            },
            async loadData(url, params) {
                const response = await fetch(url, {
                    method: 'POST',
                    body: params
                });
                let data = await response.json();
                if(data.error == 0) return data.data;
                return false;
            },

            /**
             SCREENS
             * */
            setRoomScreen() {
                if(!this.roomInfo) {
                    this.setRoomStatusEmpty();
                }
                else if(this.roomInfo && !this.hasSequence()) {
                    this.setRoomStatusFree();
                }
                else if(this.hasBusy()) {
                    this.setRoomStatusBusy();
                }
                else {
                    this.setRoomStatusWait();
                }
            },
            setSequences() {
                this.setWaitSequence();
                this.setBusySequence();
            },
            setBusySequence() {
                this.busySequence = this.roomSequence ? this.roomSequence.filter((item) => item.status_id === 3) : null;
            },
            setWaitSequence() {
                this.waitSequence = this.roomSequence ? this.roomSequence.filter((item) => item.status_id === 2) : null;
            },
            isBusy() {
                return this.roomStatus === 'busy';
            },
            isWait() {
                return this.roomStatus === 'wait';
            },
            isEmpty() {
                return this.roomStatus === 'empty';
            },
            hasBusy() {
                if(!this.roomSequence) return false;
                return this.roomSequence.some(({ status_id }) => status_id === 3)
            },
            hasWait() {
                if(!this.roomSequence) return false;
                return this.roomSequence.some(({ status_id }) => status_id === 2)
            },
            hasSequence() {
                return this.hasBusy() || this.hasWait();
            },
            setFooterText() {
                let footerText = 'Нет приёма';
                switch (this.roomStatus) {
                    case 'free':
                        footerText = 'Кабинет свободен';
                        break;
                    case 'wait':
                        footerText = 'Ожидайте вызова';
                        break;
                    case 'busy':
                        footerText = 'Идёт приём';
                        break;
                }
                this.footerText = footerText;
            },
            setRoomStatus(roomStatus) {
                this.roomStatus = roomStatus;
                this.setScreen();
                this.setFooterText();
            },
            setRoomStatusEmpty() {
                this.setRoomStatus('empty');
            },
            setRoomStatusFree() {
                this.setRoomStatus('free');
            },
            setRoomStatusWait() {
                this.setRoomStatus('wait');
            },
            setRoomStatusBusy() {
                this.setRoomStatus('busy');
            },
            inviteScreen() {
                this.setInviteMessages();
                this.showInviteScreen = true;
                setTimeout(() => {
                    this.showInviteScreen = false;
                }, this.inviteScreenTimeout)
            },
            setInviteMessages() {
                if(this.mode == 'regular') {
                    this.invite.messageTop = 'На прием в кабинет ' + this.roomNumber + ' приглашается '
                    this.invite.messageBottom = this.appointment.patient_short_name;
                }
                else if(this.mode == 'ticket') {
                    this.invite.messageTop = 'На прием в кабинет ' + this.roomNumber + ' приглашается '
                    this.invite.messageBottom = 'Пациент с талоном ' + this.appointment.ticketCode;
                }
                this.invite.fullMessage = this.invite.messageTop + this.invite.messageBottom;
            },
            setScreen() {
                let screen = 'no-schedule';
                if(this.roomStatus === 'free' || this.roomStatus === 'wait' || this.roomStatus === 'busy') {
                    screen = 'room-info';
                }
                this.screen = screen;
            },
            isScreenNoSchedule() {
                return this.screen === 'no-schedule' && !this.showInviteScreen;
            },
            isScreenRoomInfo() {
                return this.screen === 'room-info' && !this.showInviteScreen;
            },
            getAppointmentStatusText(status_id) {
                return status_id === 3 ? 'На приёме' : 'Ожидает';
            },
        }))
    });
</script>
