<template>
    <div class="p-2 bg-gray-200 ">
        <h1 class="p-2 pl-1.5">
            Stats view from OldSchool Runescape
        </h1>

        <div v-if="error" v-text="error" class="text-red-600 p-2"></div>
        <input
            class="px-2 py-1 border rounded"
            :class="{'border-green-600': !error}"
            type="text"
            v-model="playerName"
            placeholder="Enter OSRS Username"
        />
        <button class="btn bg-indigo-600 text-white px-3 py-1 rounded" @click="fetchStats">Fetch</button>
        <div class="grid md:grid-cols-4 gap-2 grid-cols-2 sm:grid-cols-3">
            <template v-for="(stats, index) in statsData" :key="index">
                <div
                    class="flex p-3 sm:p-5 gap-2 bg-white flex-col items-center border border-indigo-500/50 justify-center">
                    <div class="uppercase text-2xl" v-text="index"></div>
                    <div v-text="stats.level"></div>
                    /99
                    XP:
                    <div v-text="stats.xp"></div>
                    <div class="italic">to next level(d):
                        <br/>
                        <div v-text="stats.xpToNext"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: 'OsrsStats',
    data() {
        return {
            statsData: null,
            selectedSkill: 'strength',
            playerName: '',
            isLoading: false,
            error: null
        };
    },
    mounted() {

    },
    methods: {
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
        progressPercentage() {
            if (!this.statsData || !this.statsData?.[this.selectedSkill]) {
                return 0;
            }
            const {xp, level, xpToNext} = this.statsData?.[this.selectedSkill];
            if (level >= 99) {
                return 100;
            }
            const xpThisLevel = xp - xpToNext;
            const xpForNext = this.xpForLevel(level + 1) - this.xpForLevel(level);
            return (xpThisLevel / xpForNext) * 100;
        },
        xpForLevel(level) {
            let total = 0;
            for (let i = 1; i < level; i++) {
                total += Math.floor(i + 300 * Math.pow(2, i / 7)) / 4;
            }
            return Math.floor(total);
        },
    },
};
</script>
