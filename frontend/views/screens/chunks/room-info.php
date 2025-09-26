<template x-if="isScreenRoomInfo">
    <main class="info">
        <img class="info__bg" src="/screens/img/main-bg.jpg" alt="" />
        <div class="info__img">
            <img :src="roomInfo.avatar" alt="" />
        </div>
        <div class="info__content">
            <div class="info__content-head">
                <p class="info__name" x-text="roomInfo.doctorName"></p>
                <p class="info__specialty" x-text="roomInfo.professionsText"></p>
            </div>
            <?php
                if($mode == 'ticket') {
                    echo $this->render('ticket-sequence', [

                    ]);
                }
                else {
                    echo $this->render('regular-sequence', [

                    ]);
                }
            ?>
            <!--
            <RegularSequence v-if="roomMode === 'regular'" :sequence="roomSequence" />
            <TicketSequence v-else-if="roomMode === 'tickets'" :sequence="roomSequence" />
            -->
        </div>
    </main>
</template>

