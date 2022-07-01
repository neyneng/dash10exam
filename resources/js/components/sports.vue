<template>
    <div class="container" >
        <player-card :currentPlayer="cPlayer"
                    :team="team"
                    :teamColor="teamColor">

        </player-card>
        <navigation :allPlayers="allPlayers"
                    :cPlayer="cPlayer"
                    :nPlayer="nPlayer"
                    :pPlayer="pPlayer"
                    :teamColor="teamColor"
                    @next="nPlayer = $event"
                    @previous="pPlayer = $event"
                    @current="cPlayer = $event">
        </navigation>
    </div>
</template>
<script setup>
import Navigation from  "./navigation.vue";
import PlayerCard from  "./playercard.vue";
import {ref, onMounted} from "vue"

const props = defineProps({
    type:Number
})

const cPlayer = ref({});
const pPlayer = ref({});
const nPlayer = ref({});
const allPlayers = ref({});
const apiUrl = ref('');
const team = ref('');
const teamColor = ref('');


onMounted(() => {
    getAllPlayers()

});

const getAllPlayers = async () => {

    if(props.type === 1){
        apiUrl.value = '/rugby/allblacks';
        team.value = 'allblacks';
        teamColor.value = 'black'

    }else{
        apiUrl.value = '/nba/gsw';
        team.value = 'gsw';
        teamColor.value = '#1d4487'
    }

    let res = await axios.get( apiUrl.value);
    cPlayer.value = res.data[0];
    pPlayer.value = res.data[res.data.length - 1];
    nPlayer.value = res.data[1];
    allPlayers.value = res.data;
}

</script>
