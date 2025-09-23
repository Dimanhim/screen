<div>
    <header class="header">
        <template x-if="roomNumber">
            <p class="header__num">
                № <span class="header__num-val" x-text="roomNumber"></span> <span x-if="roomInfo.name" x-text="roomInfo.name"> / </span>
            </p>
        </template>

        <div class="header__logo" @click="customActions">
            <img src="/screens/img/logo.png" alt="" />
        </div>
    </header>
</div>
