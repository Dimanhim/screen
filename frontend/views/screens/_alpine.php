<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('screens', () => ({
            roomId: '<?= $roomId ?>',
            mode: '<?= $mode ?>',

            client: null,

            roomName: null,
            avatar: null,
            professionsText: null,
            appointments: null,

            footerText: null,
            roomStatuses: ['empty', 'free', 'wait', 'busy'],
            roomStatus: null,
            screens: ['no-schedule', 'room-info', 'invite'],
            screen: null,
            showInviteScreen: false,
            inviteScreenTimeout: 5000,
            invite: {                                           // инфа по приглашению audio
                messageTop: 'Пациент с талоном',
                messageBottom: 'Л001',
                audio: null
            },

            roomInfo: null,                                     // объект - инфа по комнате
            roomSequence: null,                                 // массив визитов
            busySequence: null,
            waitSequence: null,

            // roomInfoTest: {
            //      name: 'Кабинет Невролога',
            //      avatar: 'https://files.rnova.org/198733bd446bb513a3bfe91ae1f3d391/2f3988fbcf0519ea27fdcefaf0d1772d.png',
            //      professionsText: 'Врач высшей категории',
            // },

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

            setDefault() {
                //this.setRoomStatusWait();
                this.client = client;
                this.getAppointments(() => {
                    this.getRoomInfo(() => {
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
                        this.setBusySequence();
                        this.setWaitSequence();
                        this.client.init(this.roomId)
                    })
                })
            },
            /*listen() {
                this.client.listen();
            },*/
            getAppointments(callback) {
                const params = new URLSearchParams();
                params.set('roomId', this.roomId);
                const response = this.loadData('/api/get-appointments', params)
                response.then((data) => {
                    this.roomSequence = data;
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
            getRoomInfo(callback) {
                if(this.roomInfo) return;
                const params = new URLSearchParams();
                params.set('roomId', this.roomId);
                const response = this.loadData('/api/get-room', params)
                response.then((data) => {
                    this.roomInfo = data;
                    callback();
                })
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

            // roomStatus
            setRoomStatus(roomStatus) {
                // здесь инфа берется из сокета
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

            // screens
            inviteScreen() {
                this.showInviteScreen = true;
                // набор действий
                setTimeout(() => {
                    this.showInviteScreen = false;
                }, this.inviteScreenTimeout)
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

            customActions() {
                this.inviteScreen()
                //this.setRoomStatusWait();
                //this.setRoomStatusBusy();
                //this.setRoomStatusFree();
            },

            getAppointmentStatusText(status_id) {
                return status_id === 3 ? 'На приёме' : 'Ожидает';
            },
        }))
    });
</script>
