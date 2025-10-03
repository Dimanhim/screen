
    <div class="screen__content">
        <template x-if="isScreenRoomInfo">
        <div class="screen__grid">
            <div class="photo"><img :src="roomInfo.avatar" width="310" height="310" alt=""></div>
            <div class="screen__name" x-text="roomInfo.doctorName"></div>
            <div class="screen__subtitle" x-text="roomInfo.professionsText"></div>
        </div>
        </template>
        <div class="qr">
                <div id="qrcode" class="qr__code"></div>
            <template x-if="roomInfo">
                <div class="qr__title">Подробнее о специалисте</div>
                <div class="qr__desc">Узнайте подробную информацию об опыте и образовании специалиста</div>
            </template>
        </div>
    </div>
    <style>
        #qrcode  {
            margin-top: -10px;
        }
        .qr__title,
        .qr__desc
        {
            margin-left: 20px;
        }

    </style>




