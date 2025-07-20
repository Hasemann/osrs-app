<template>
    <div class="flex gap-2 items-end flex-row">
        <button @click="$emit('updateLayout', 'table')" class="btn max-h-12 group transition hover:bg-black border border-gray-200">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="4" width="18" height="3"/>
                <rect x="3" y="10.5" width="18" height="3"/>
                <rect x="3" y="17" width="18" height="3"/>
            </svg>
        </button>
        <button @click="$emit('updateLayout', 'grid')" class="btn max-h-12  group transition hover:bg-black border border-gray-200 ">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="3" width="8" height="8"/>
                <rect x="13" y="3" width="8" height="8"/>
                <rect x="3" y="13" width="8" height="8"/>
                <rect x="13" y="13" width="8" height="8"/>
            </svg>
        </button>
    </div>
</template>
<script>

export default {

}
</script>
