<template x-if="!showInviteScreen">
    <div class="screen__header">
        <template x-if="roomInfo">
            <div class="screen__header-number" x-text="roomInfo.number"></div>
        </template>

        <div class="screen__header-status">
            <span x-html="footerText"></span>
            <template x-if="isEmpty()">
                <svg class="screen__header-icon"><use xlink:href="#close"></use></svg>
            </template>
            <template x-if="isWait()">
                <svg class="screen__header-icon"><use xlink:href="#plus"></use></svg>
            </template>
            <template x-if="isBusy()">
                <div class="screen__header-icon ">
                    <svg class="loader" viewBox="0 0 108 108" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="49.95" width="8.1" height="27" fill="#2D282A" />
                        <rect y="58.05" width="8.1" height="27" transform="rotate(-90 0 58.05)" fill="#2D282A" />
                        <rect x="58.05" y="108" width="8.1" height="27" transform="rotate(180 58.05 108)" fill="#2D282A" />
                        <rect x="108" y="49.95" width="8.1" height="27" transform="rotate(90 108 49.95)" fill="#2D282A" />
                        <rect x="12.6343" y="18.9988" width="8.1" height="27" transform="rotate(-45 12.6343 18.9988)" fill="#2D282A" />
                        <rect x="18.998" y="95.3662" width="8.1" height="27" transform="rotate(-135 18.998 95.3662)" fill="#2D282A" />
                        <rect x="95.3657" y="89.0012" width="8.1" height="27" transform="rotate(135 95.3657 89.0012)" fill="#2D282A" />
                        <rect x="89.0016" y="12.6338" width="8.1" height="27" transform="rotate(45 89.0016 12.6338)" fill="#2D282A" />
                    </svg>
                </div>
            </template>
        </div>
    </div>
</template>

