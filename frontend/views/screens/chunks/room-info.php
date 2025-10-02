<template x-if="isScreenRoomInfo">
    <div class="screen__content">
        <div class="screen__grid">
            <div class="photo"><img :src="roomInfo.avatar" width="310" height="310" alt=""></div>
            <div class="screen__name" x-text="roomInfo.doctorName"></div>
            <div class="screen__subtitle" x-text="roomInfo.professionsText"></div>
        </div>
        <div class="qr">
            <img class="qr__code" src="/alfa/img/qr-code.svg" alt="">
            <div class="qr__title">Подробнее о специалисте</div>
            <div class="qr__desc">Узнайте подробную информацию об опыте и образовании специалиста</div>
        </div>
    </div>
</template>

