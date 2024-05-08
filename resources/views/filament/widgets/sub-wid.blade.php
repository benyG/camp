<x-filament-widgets::widget class="fi-filament-info-widget">
<x-filament::section>
<div class="text-center " x-data="{
        duration: 10000,
        active: 0,
        progress: 0,
        firstFrameTime: 0,
        items: $wire.items,
        init() {
            this.startAnimation()
            this.$watch('active', callback => {
                cancelAnimationFrame(this.frame)
                this.startAnimation()
            })
        },
        startAnimation() {
            this.progress = 0
            this.$nextTick(() => {
                this.heightFix()
                this.firstFrameTime = performance.now()
                this.frame = requestAnimationFrame(this.animate.bind(this))
            })
        },
        animate(now) {
            let timeFraction = (now - this.firstFrameTime) / this.duration
            if (timeFraction <= 1) {
                this.progress = timeFraction * 100
                this.frame = requestAnimationFrame(this.animate.bind(this))
            } else {
                timeFraction = 1
                this.active = (this.active + 1) % this.items.length
            }
        },
        heightFix() {
            this.$nextTick(() => {
              this.$refs.items.parentElement.style.height = this.$refs.items.children[this.active + 1].clientHeight + 'px'
            })
        }
    }" x-init="init();"
>
    <!-- Item image -->
    <div class="transition-all duration-150 ease-in-out delay-300">
        <div class="relative flex flex-col" x-ref="items">
            <!-- Alpine.js template for items: https://github.com/alpinejs/alpine#x-for -->
            <template x-for="(item, index) in items" :key="index">
                <div
                    x-show="active === index"
                    x-transition:enter="transition ease-in-out duration-500 delay-200 order-first"
                    x-transition:enter-start="opacity-0 scale-105"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in-out duration-300 absolute"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    <a href="item.url#" target="_blank" x-html="item.text"></a>
                </div>
            </template>
        </div>
    </div>
    <!-- Buttons -->
</div>
</x-filament::section>
</x-filament-widgets::widget>
