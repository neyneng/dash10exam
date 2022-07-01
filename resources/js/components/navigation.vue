<template>
    <nav class="nav">
        <ul>
            <li :style="`background-color: ${teamColor}`"><a  @click="getPreviousPlayer()" href="#">{{ pPlayer['first_name'] }} {{ pPlayer['last_name'] }}</a></li>
            <li class="active"><a href="#">{{ cPlayer['first_name'] }} {{ cPlayer['last_name'] }}</a></li>
            <li :style="`background-color: ${teamColor}`"><a @click="getNextPlayer" href="#">{{ nPlayer['first_name'] }} {{ nPlayer['last_name'] }}</a></li>
        </ul>
    </nav>
</template>
<script setup>
import {onUpdated, ref} from 'vue';

    const props = defineProps({
        allPlayers: Object,
        pPlayer: Object,
        nPlayer: Object,
        cPlayer: Object,
        teamColor: String
    });

    const emit = defineEmits(["previous","next","current"]);

    const index = ref(0);
    const prevIndex = ref(0);
    const nextIndex = ref(0);
    const currentPlayer = ref({});
    const previousPlayer = ref({});
    const nextPlayer = ref({});

    const getNextPlayer = () => {
        prevIndex.value = index.value;
        index.value = index.value + 1;
        index.value = index.value % props.allPlayers.length;
        currentPlayer.value = props.allPlayers[index.value];
        previousPlayer.value = props.allPlayers[prevIndex.value];

        if (index.value === props.allPlayers.length - 1) {
            nextPlayer.value = props.allPlayers[0];
        } else {
            nextPlayer.value = props.allPlayers[index.value + 1]
        }

        emit('current', currentPlayer.value)
        emit('previous', previousPlayer.value)
        emit('next', nextPlayer.value)
    }

    const getPreviousPlayer = () => {
        nextIndex.value = index.value;
        if (index.value === 0) {
            index.value = props.allPlayers.length;
        }
        index.value = index.value - 1;
        currentPlayer.value = props.allPlayers[index.value];
        nextPlayer.value = props.allPlayers[nextIndex.value]

        if (index.value === 0) {
            previousPlayer.value = props.allPlayers[props.allPlayers.length - 1];
        }else{
            previousPlayer.value = props.allPlayers[index.value - 1];
        }

        emit('current', currentPlayer.value)
        emit('previous', previousPlayer.value)
        emit('next', nextPlayer.value)
    }
</script>

