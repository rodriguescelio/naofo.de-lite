import Vue from 'vue'
import Vuex from 'vuex'
import params from './params'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        url: null,
        title: params.site_name + ' | Seu encurtador higiênico de chorume',
        config: params,
        archive: 'https://web.archive.org'
    },
    mutations: {
        setPageTitle(state, data) {
            state.pageTitle = data
        },
        setUrl(state, url) {
            state.url = url
        }
    },
    actions: {
        loadPageTitle({commit, state}) {
            document.title = state.title
        }
    }
})

export default store