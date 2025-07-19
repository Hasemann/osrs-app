<template>
    <div class="p-2 bg-gray-200 ">
        <h1 class="p-2 pl-1.5">
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
                    class="flex p-3 sm:p-5 gap-2 bg-white flex-col items-center border border-indigo-500/50 justify-center">
                    <div class="uppercase text-2xl" v-text="index"></div>
                    <div class="flex flex-row">
                        <div v-text="stats.level"></div>
                        <template v-if="index !== 'overall'">
                            <div>/99</div>
                        </template>
                    </div>
                    <div class="flex flex-row">
                        <div>XP:</div>
                        <div v-text="stats.xp"></div>
                    </div>
                    <template v-if="index !== 'overall'">
                        <div class="flex flex-row text-xs italic">to next level(
                            <div v-text="stats.level + 1"></div>
                            )
                            <br/>
                            <div v-text="stats.totalXpToNext - stats.xp"></div>
                        </div>
                        <progress :value="progressPercentage(stats)" max="100"></progress>

                    </template>
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
                    this.error = null
                }

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
