<template>
    <!-- New Table -->
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <slot name="thead"></slot>
                <slot name="tbody"></slot>
            </table>
        </div>
        <slot name="pagination"></slot>
    </div>
</template>
