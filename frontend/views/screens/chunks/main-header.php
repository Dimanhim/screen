<div>
    <header class="header">
        <p class="header__num" v-if="roomNumber">
            № <span class="header__num-val" x-text="roomNumber"></span> <span v-if="roomInfo.name" x-text="roomInfo.name"> / </span>
        </p>
        <div class="header__logo" @click="customActions">
            <img src="/screens/img/logo.png" alt="" />
        </div>
    </header>
</div>
