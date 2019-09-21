<template>
    <div class="mr-8">
        <button class="rounded-full w-4 h-4 bg-page mr-2 focus:outline-none" style="background:#fff;border:1px solid #ccc;" @click="selectedTheme = 'theme-light'"></button>
        <button class="rounded-full w-4 h-4 bg-page mr-2 focus:outline-none" style="background:#222;border:1px solid #ccc;" @click="selectedTheme = 'theme-dark'"></button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selectedTheme: 'theme-light'
            };
        },

        created() {
            this.selectedTheme = localStorage.getItem('theme') || 'theme-light';
        },

        watch: {
            selectedTheme() {
                document.body.className = document.body.className.replace(/theme-\w+/, this.selectedTheme);

                localStorage.setItem('theme', this.selectedTheme);
            }
        }
    }
</script>
