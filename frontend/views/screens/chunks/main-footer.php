<template x-if="!showInviteScreen">
    <footer
        class="footer"
        :class="{
          'footer--busy': isBusy(),
          'footer--wait': isWait()
        }"
        x-html="footerText"
    >
    </footer>
</template>
