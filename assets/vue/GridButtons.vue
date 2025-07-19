<template>
    <div class="flex gap-2 flex-row">
        <button @click="$emit('updateGrid', 3)" class="btn group transition hover:bg-black border border-black ">
            <svg width="40" height="30" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10" cy="15" r="3" class="group-hover:fill-white transition fill-black"/>
                <circle cx="20" cy="15" r="3" class="group-hover:fill-white transition  fill-black"/>
                <circle cx="30" cy="15" r="3" class="group-hover:fill-white transition fill-black"/>
            </svg>
        </button>
        <button @click="$emit('updateGrid', 4)" class="btn group transition hover:bg-black border border-black ">
            <svg width="50" height="30" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10" cy="15" r="3" class="group-hover:fill-white transition fill-black"/>
                <circle cx="20" cy="15" r="3" class="group-hover:fill-white transition fill-black"/>
                <circle cx="30" cy="15" r="3" class="group-hover:fill-white transition fill-black"/>
                <circle cx="40" cy="15" r="3" class="group-hover:fill-white transition fill-black"/>
            </svg>
        </button>
    </div>
</template>
<script>

export default {

}
</script>
