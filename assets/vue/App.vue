<template>
    <div class="bg-charcoal h-full w-max-full overflow-hidden">
        <div class="p-3 bg-darkNavy">

            <h1 class="p-2 text-softYellow pl-1.5">
                Stats view from OldSchool Runescape
            </h1>
            <div class="flex flex-row justify-between">
                <div>
                    <div v-if="error" v-text="error" class="text-red-600 py-2"></div>
                    <input
                        class="px-2 py-1  bg-transparent border-softYellow text-softYellow border"
                        :class="{
                        'border-green-600': error === '',
                        'border-red-600': error,
                    }"
                        type="text"
                        v-model="playerName"
                        placeholder="Enter OSRS Username"
                    />
                    <button class="btn  bg-transparent border border-softYellow text-softYellow px-3 py-1"
                            @click="fetchStats">Fetch
                    </button>
                </div>
                <div class="flex gap-5 flex-row">
                    <layout-buttons @updateLayout="toggleLayout"></layout-buttons>
                    <div v-if="layout === 'grid'">
                        <grid-buttons @updateGrid="toggleGrid"></grid-buttons>
                    </div>
                </div>
            </div>
        </div>


        <div v-if="layout === 'grid'" class="grid gap-2  bg-charcoal grid-cols-2 mt-2"
             :class="gridClass">
            <template v-for="(stats, index) in statsData" :key="index">
                <div
                    class="flex p-3 justify-between sm:p-5 gap-2 h-24 bg-slateBlue flex-col text-softYellow items-start">
                    <div class="flex justify-between  w-full flex-row text-xs">
                        <div class="relative" v-text="'Level ' +  stats.level"></div>
                        <div class="flex justify-end w-full items-center">
                            <template v-if="index === 'runecrafting'">
                                <img class="!h-6 w-auto"
                                     src="https://raw.githubusercontent.com/runelite/runelite/master/runelite-client/src/main/resources/skill_icons/runecraft.png"
                                     alt="Runecraft icon"/>
                            </template>
                            <template v-if="index !=='runecrafting'">
                                <img class="!h-6 w-auto"
                                     :src="`https://raw.githubusercontent.com/runelite/runelite/master/runelite-client/src/main/resources/skill_icons/${index}.png`"
                                     :alt="`${index} icon`"/>
                            </template>
                        </div>
                    </div>

                    <div class="flex group items-end relative bottom-0 h-8 w-full">
                        <template v-if="index !== 'overall'">
                            <progress class="h-full w-full"
                                      :value="progressPercentage(stats)"
                                      max="100">
                            </progress>
                        </template>

                        <div
                            class="capitalize text-softYellow absolute top-0 left-0 h-full flex justify-center items-center w-full text-xs mr-3"
                            v-text="index"></div>
                        <template v-if="index !== 'overall'">
                            <div
                                class="capitalize text-softYellow absolute top-0 left-0 h-full flex justify-end items-center w-full text-xs pr-2"
                                v-text="parseFloat(progressPercentage(stats).toFixed(2)) + '%'"></div>
                            <div
                                class="hidden group-hover:flex top-[-55px] absolute border border-gray-200 bg-gray-800 p-4 flex-row text-xs">
                                <div v-text="stats.totalXpToNext - stats.xp + ' exp '"></div>
                                <div class="ml-1 mr-1">to level</div>
                                <div v-text="stats.level + 1"></div>
                                <br/>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <div v-else-if="layout === 'table'" class="flex gap-2 h-full text-softYellow text-xs flex-col mt-2">
            <div class="grid grid-cols-4">
                <div class="text-base px-2 col-span-4 grid grid-cols-4 pb-3 border-b border-gray-200">
                    <div class="col-span-2 text-grey-200">Stats</div>
                    <div class="col-span-1 text-center text-grey-200">Level</div>
                    <div class="col-span-1 text-center text-grey-200">Progress</div>
                </div>
                <template v-for="(stats, index, key) in statsData" :key="key">
                    <div class="flex flex-row items-center py-2 col-span-2"
                         :class="key % 2 === 0 ? 'bg-slateBlue' : 'bg-charcoal'">
                        <div class="p-2 " v-if="index === 'runecrafting'">
                            <img class="!w-6 h-auto"
                                 src="https://raw.githubusercontent.com/runelite/runelite/master/runelite-client/src/main/resources/skill_icons/runecraft.png"
                                 alt="Runecraft icon"/>
                        </div>
                        <div class="p-2" v-if="index !=='runecrafting'">
                            <img class="!w-6 h-auto"
                                 :src="`https://raw.githubusercontent.com/runelite/runelite/master/runelite-client/src/main/resources/skill_icons/${index}.png`"
                                 :alt="`${index} icon`"/>
                        </div>
                        <div
                            class="capitalize text-softYellow pl-3 h-full flex items-center w-full text-xs mr-3"
                            v-text="index"></div>
                    </div>
                    <div class="col-span-1 py-2 flex justify-center"
                         :class="key % 2 === 0 ? 'bg-slateBlue' : 'bg-charcoal'">
                        <div class="text-center self-center" v-text="stats.level"></div>

                    </div>
                    <div class="col-span-1 flex items-center py-2" :class="key % 2 === 0 ? 'bg-slateBlue' : 'bg-charcoal'">
                        <div class="flex group pr-2 items-end relative bottom-0 h-6 w-full">
                            <template v-if="index !== 'overall'">
                                <progress class="h-full w-full border border-[#222020] rounded "
                                          :value="progressPercentage(stats)"
                                          max="100">
                                </progress>
                            </template>

                            <template v-if="index !== 'overall'">
                                <div
                                    class="capitalize text-softYellow absolute top-0 h-full flex justify-end items-center w-full text-xs right-4"
                                    v-text="parseFloat(progressPercentage(stats).toFixed(2)) + '%'"></div>
                                <div
                                    class="hidden group-hover:flex top-[-55px] absolute  bg-charcoal p-4 flex-row text-xs">
                                    <div v-text="stats.totalXpToNext - stats.xp + ' exp '"></div>
                                    <div class="ml-1 mr-1">to level</div>
                                    <div v-text="stats.level + 1"></div>
                                    <br/>
                                </div>
                            </template>
                        </div>
                    </div>

                </template>
            </div>
        </div>

    </div>
</template>

<script>
import GridButtons from "./GridButtons.vue";
import LayoutButtons from "./LayoutButtons.vue";

export default {
    name: 'OsrsStats',
    components: {GridButtons, LayoutButtons},
    data() {
        return {
            grid: 4,
            layout: 'table',
            statsData: null,
            exp: 0,
            expToNext: 0,
            progress: 0,
            playerName: '',
            isLoading: false,
            error: null
        };
    },
    computed: {
        gridClass() {
            return {
                'md:grid-cols-3': this.grid === 3,
                'md:grid-cols-4': this.grid === 4,
            }
        }
    },
    mounted() {
        if (localStorage.getItem('stats')) {
            this.statsData = JSON.parse(localStorage.getItem('stats'))
        }
    },
    methods: {
        toggleGrid(gridInput) {
            this.grid = gridInput
        },

        toggleLayout(layoutInput) {
            this.layout = layoutInput
        },

        async fetchStats() {
            try {
                if (!this.playerName || this.playerName.trim() === '') {
                    this.error = 'Please enter a valid player name.';
                    return;
                }
                const response = await fetch(`/api/osrs/${this.playerName}`);
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    this.error = errorData.errorMessage;
                } else {
                    this.statsData = await response.json();
                    localStorage.setItem('stats', JSON.stringify(this.statsData));
                    this.error = ''
                }
                // todo remove
            } catch (error) {
                console.error('Error fetching stats:', error);
                this.error = error.data
            }
        },
        setPlayerName(name) {
            this.playerName = name;
        },
        progressPercentage(stats) {
            if (!stats || stats.level >= 99) {
                return 100;
            }
            const xpTowardsNext = stats.xp - stats.startingXp;
            const xpNeededForNext = stats.totalXpToNext - stats.startingXp;
            const progress = (xpTowardsNext / xpNeededForNext) * 100;
            return Math.min(100, Math.max(0, progress));
        },
    },
};
</script>
