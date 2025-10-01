<div>
    <header class="header">
        <template x-if="roomInfo">
            <p class="header__num">
                № <span class="header__num-val" x-text="roomInfo.number"></span>
                <template x-if="roomInfo">
                    <span x-text="roomInfo.name"> / </span>
                </template>
            </p>
        </template>

        <div class="header__logo" @click="customActions">
            <img src="/screens/img/logo.svg" alt="" />
        </div>
    </header>
</div>
