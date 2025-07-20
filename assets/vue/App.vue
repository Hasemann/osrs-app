<template>
    <div class="p-2 bg-gray-900">
        <h1 class="p-2 text-gray-300 pl-1.5">
            Stats view from OldSchool Runescape
        </h1>
        <div class="flex flex-row justify-between">
            <div>
                <div v-if="error" v-text="error" class="text-red-600 p-2"></div>
                <input
                    class="px-2 py-1 border"
                    :class="{'border-green-600': !error}"
                    type="text"
                    v-model="playerName"
                    :value="brokedeveloper"
                    placeholder="Enter OSRS Username"
                />
                <button class="btn bg-indigo-600 text-white px-3 py-1" @click="fetchStats">Fetch</button>
            </div>
            <grid-buttons @updateGrid="toggleGrid"></grid-buttons>
        </div>

        <div class="grid gap-2 grid-cols-2 mt-2"
             :class="gridClass">

            <template v-for="(stats, index) in statsData" :key="index">
                <div
                    class="flex p-3 justify-between sm:p-5 gap-2 h-24 bg-gray-800 flex-col text-gray-200 items-start">
                    <div class="flex justify-between w-full flex-row text-xs">
                        <div class="relative" v-text="'Level ' +  stats.level"></div>
                        <div class="flex justify-end w-full items-center">
                            <img class="!h-6 w-auto" :src="`https://raw.githubusercontent.com/runelite/runelite/master/runelite-client/src/main/resources/skill_icons/${index}.png`" :alt="`${index} icon`" />
                        </div>
                    </div>

                    <div class="flex group items-end relative bottom-0  border border-[#222020] h-8 w-full">
                        <template v-if="index !== 'overall'">
                            <progress class="h-full w-full border border-[#222020] rounded bg-gray-200"
                                      :value="progressPercentage(stats)"
                                      max="100">
                            </progress>
                        </template>

                        <div
                            class="capitalize text-gray-200 absolute top-0 left-0 h-full flex justify-center items-center w-full text-xs mr-3"
                            v-text="index"></div>
                        <template v-if="index !== 'overall'">
                            <div
                                class="capitalize text-gray-200 absolute top-0 left-0 h-full flex justify-end items-center w-full text-xs pr-2"
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
    </div>
</template>

<script>
import GridButtons from "./GridButtons.vue";

export default {
    name: 'OsrsStats',
    components: {GridButtons},
    data() {
        return {
            grid: 4,
            statsData: null,
            exp: 0,
            expToNext: 0,
            progress: 0,
            playerName: 'brokedevelop',
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

        async fetchStats() {
            try {
                if (!this.playerName || this.playerName.trim() === '') {
                    this.error = 'Please enter a valid player name.';
                    this.statsData = null;
                    return;
                }
                const response = await fetch(`/api/osrs/${this.playerName}`);
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    this.error = errorData.errorMessage;
                } else {
                    this.statsData = await response.json();
                    localStorage.setItem('stats', JSON.stringify(this.statsData));
                    console.log(this.statsData)
                    this.error = null
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
