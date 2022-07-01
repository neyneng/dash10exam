<template>
    <div class="card" :style="`border-top: solid 0.75rem ${teamColor}`">
        <img :src="`/images/teams/${team}.png`" class="logo" />
        <div class="name">
            <em>#{{ currentPlayer['number'] }}</em>
            <h2>{{ currentPlayer['first_name'] }} <strong>{{ currentPlayer['last_name'] }}</strong></h2>
        </div>
        <div class="profile">
            <img :src="`/images/players/${team}/${currentPlayer['first_name']}-${currentPlayer['last_name']}.png`" class="headshot" />
            <div class="features">

                <div v-for="(feature, name) in currentPlayer['featured']" class="feature">
                    <h3>{{name}}</h3>
                    {{ feature }}
                </div>

            </div>
        </div>
        <div class="bio">
            <div  v-for="(stat, name) in currentPlayer['stats']"  class="data">
                <strong>{{ name }}</strong>
                {{ stat }}
            </div>

        </div>
    </div>
</template>
<script setup>
    const props = defineProps({
       currentPlayer: Object,
        team: String,
        teamColor: String
    });
</script>

