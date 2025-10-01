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
            invite: {
                fullMessage: null,
                messageTop: null,
                messageBottom: null,
                audio: null
            },

            roomInfo: null,
            roomSequence: null,
            busySequence: null,
            waitSequence: null,

            socketUrl: '<?= \Yii::$app->settings->getParam('socket_url') ?>',
            socketConn: null,

            /**
             INIT
             * */
            initDefault() {
                this.setDefault();
                this.createSocketConnection();
            },
            setDefault() {
                this.getRoomInfo(() => {
                    this.getAppointments(() => {
                        this.setRoomScreen();
                    })
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
                };
                this.socketConn.onerror = function (e) {

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
                switch (method) {
                    case 'register':
                        break;
                    case 'update':
                        this.setAppointment(data.data, () => {
                            app.handleUpdate();
                        })
                        break;
                    case 'notification':
                        this.setAppointment(data.data, () => {
                            app.handleNotification();
                        })
                        break;
                }
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
                this.appointment = data;
                callback();
                return;
            },
            handleUpdate() {
                this.setDefault();
            },
            handleNotification() {
                this.setDefault();
                //this.inviteScreen();
            },

            /**
             API
             * */
            getAppointments(callback) {
                const params = new URLSearchParams();
                params.set('roomId', this.roomId);
                params.set('doctorId', this.roomInfo.id);
                const response = this.loadData('/api/get-appointments', params)
                response.then((data) => {
                    this.roomSequence = data;
                    this.setSequences();
                    callback();
                })
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
                else if(this.mode == 'tickets') {
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
