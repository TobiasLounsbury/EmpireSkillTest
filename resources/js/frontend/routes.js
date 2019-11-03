import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import Leaderboard from './pages/Leaderboard'
import GamesList from './pages/GamesList'
import GameConfig from './pages/GameConfig'
import PlayGame from './pages/PlayGame'
import WatchGame from './pages/WatchGame'
import PlayerDetail from './pages/PlayerDetail'

const router = new VueRouter({
    mode: 'hash',
    routes: [
        {
            path: '/',
            name: 'index',
            component: GamesList
        },
        {
            path: '/leaderboard',
            name: 'leaderboard',
            component: Leaderboard,
        },
        {
            path: '/games',
            name: 'list-games',
            component: GamesList,
        },
        {
            path: '/game/:gameId',
            name: 'play-game',
            component: PlayGame,
        },
        {
            path: '/start-a-game',
            name: 'new-game',
            component: GameConfig,
        },
        {
            path: '/watch/:gameId',
            name: 'watch-game',
            component: WatchGame,
        },
        {
            path: '/player/:playerId',
            name: 'player-detail',
            component: PlayerDetail,
        },
    ],
});

export default router;
