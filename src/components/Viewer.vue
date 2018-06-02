<template>
	<div class="viewer">
		<div class="bar" :class="{hide: isHideBox, invisible: loadingHide}">
			<a href="#" @click="toggleBar" class="arrow">
				<i class="fa" :class="isHideBox ? 'fa-arrow-right' : 'fa-arrow-left'"></i>
			</a>
			<h1 @click="$router.push({path: '/'})">{{ config.site_name }}</h1>
			<h3>Encurtador higiênico de chorume</h3>
			<ul>
				<li>
					<b>Código:</b>
					<span>{{ hash }}</span>
				</li>
				<li>
					<b>Titulo:</b>
					<span>{{ page.title }}</span>
				</li>
				<li>
					<b>Capturado em:</b>
					<span>{{ page.time }}</span>
				</li>
				<li>
					<b>Acessos:</b>
					<span>{{ page.acessos }}</span>
				</li>
			</ul>
		</div>
		<iframe :class="{invisible: loadingHide}" :src="page.url" @load="hideLoading" frameborder="0"></iframe>
		<loading
			:show="loading"
			label="Carregando captura...">
		</loading>
	</div>
</template>

<script>
import loading from 'vue-full-loading'
import { mapState } from 'vuex'
import moment from 'moment'
export default {
	name: 'Viewer',
	props: ['hash'],
	components: {
		loading
	},
	computed: {
		...mapState(['config', 'archive'])
	},
	methods: {
		toggleBar(event) {
			event.preventDefault();
			this.isHideBox = !this.isHideBox
		},
		hideLoading() {
			this.loading = false
		}
	},
	mounted() {
		this.loading = true
		this.loadingHide = true
		database.ref('saves/' + this.hash)
		.once('value').then((snapshot) => {
			let page = snapshot.val()
			
			page.acessos++;

			database.ref('saves/' + this.hash).update(page)
			
			page.url = this.archive + page.url

			let date = new Date();
				date.setTime(page.time)

			page.time = moment(date).format('HH:mm DD/MM/YYYY')
			this.page = page

			this.loadingHide = false
		})
	},
	data () {
		return {
			loading: false,
			isHideBox: false,
			loadingHide: false,
			page: {
				title: null,
				acessos: null,
				url: null
			}
		}
	}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="less">
	body {
		overflow: hidden;
	}
	.viewer {
		width: 100%;
		height: 100%;
		position: relative;

		.invisible {
			visibility: hidden;
		}

		.bar {
			position: fixed;
			top: 0;
			left: 0;
			width: 270px;
			min-height: 100px;
			background: #f1f1f1;
			box-shadow: 0 0 10px rgba(0, 0, 0, .5);
			border-bottom-right-radius: 10px;

			box-sizing: border-box;
			padding: 10px 20px;
			transition: left .25s ease-out;

			&.hide {
				left: -270px;
				box-shadow: none;
			}

			.arrow {
				position: absolute;
				right: -30px;
				background: #f1f1f1;
				padding: 7px 8px;
				border-top-right-radius: 3px;
				border-bottom-right-radius: 3px;
				box-shadow: 4px 0 10px -5px rgba(0, 0, 0, .5);
				color: #333;
			}

			h1, h3 {
				display: inline-block;
				width: 100%;
				text-align: center;
				margin-top: 0;
			}

			h1 {
				font-size: 25px;
				margin-bottom: 0;
				cursor: pointer;
			}

			h3 {
				font-weight: 300;
				font-size: 12px;
			}

			ul {
				list-style-type: none;
				padding: 0;
				margin: 0;

				li {
					font-size: 14px;
					letter-spacing: .5px;
					line-height: 1.5;
					b {
						color: #4c4c4c;
					}
					span {
						color: #333;
					}
				}

			}

		}

		iframe {
			width: 100%;
			height: 100%;
		}

	}
</style>
