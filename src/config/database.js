import firebase from 'firebase'
import config from './params'

let app = firebase.initializeApp(config.firebase)

export default app.database()