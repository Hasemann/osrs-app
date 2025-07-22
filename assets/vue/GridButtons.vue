<template>
    <div class="flex gap-2 items-end flex-row">
        <button @click="$emit('updateGrid', 3)" class="btn max-h-12 group transition hover:bg-black border border-gray-200">
            <svg width="40" height="25" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10" cy="13" r="3" class="group-hover:fill-white transition fill-gray-200"/>
                <circle cx="20" cy="13" r="3" class="group-hover:fill-white transition  fill-gray-200"/>
                <circle cx="30" cy="13" r="3" class="group-hover:fill-white transition fill-gray-200"/>
            </svg>
        </button>
        <button @click="$emit('updateGrid', 4)" class="btn max-h-12  group transition hover:bg-black border border-gray-200 ">
            <svg width="50" height="25" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10" cy="13" r="3" class="group-hover:fill-white transition fill-gray-200"/>
                <circle cx="20" cy="13" r="3" class="group-hover:fill-white transition fill-gray-200"/>
                <circle cx="30" cy="13" r="3" class="group-hover:fill-white transition fill-gray-200"/>
                <circle cx="40" cy="13" r="3" class="group-hover:fill-white transition fill-gray-200"/>
            </svg>
        </button>
    </div>
</template>
<script>

export default {

}
</script>
