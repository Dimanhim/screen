<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('screens', () => ({
            roomId: '<?= $roomId ?>',
            roomNumber: '<?= $roomNumber ?>',
            mode: '<?= $mode ?>',

            roomName: null,
            avatar: null,
            professionsText: null,

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

            //roomInfo: null,                                     // объект - инфа по комнате
            //roomSequence: null,                                 // массив визитов
            busySequence: null,
            waitSequence: null,

            roomInfo: {
                name: 'Кабинет офтальмолога',
                avatar: 'https://files.rnova.org/198733bd446bb513a3bfe91ae1f3d391/2f3988fbcf0519ea27fdcefaf0d1772d.png',
                professionsText: 'Врач высшей категории',
            },
            roomSequence: [
                {
                    id: 1111,
                    status_id: 4,
                    time_start: '15:00',
                    patientNumber: '555',
                    ticketCode: 'Л001',
                },
                {
                    id: 2222,
                    status_id: 3,
                    time_start: '15:30',
                    patientNumber: '444',
                    ticketCode: 'Л002',
                },
                {
                    id: 5555,
                    status_id: 4,
                    time_start: '16:00',
                    patientNumber: '333',
                    ticketCode: 'Л003',
                },
            ],

            setDefault() {
                //this.setRoomStatusWait();
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
            },
            setBusySequence() {
                this.busySequence = this.roomSequence.filter((item) => item.status_id === 3);
            },
            setWaitSequence() {
                this.waitSequence = this.roomSequence.filter((item) => item.status_id === 4);
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
                return this.roomSequence.some(({ status_id }) => status_id === 4)
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
