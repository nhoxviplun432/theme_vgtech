import Swup from 'swup'
import SwupHeadPlugin from '@swup/head-plugin'
import SwupBodyClassPlugin from '@swup/body-class-plugin'

// KHÔNG import @swup/scripts-plugin nữa
const swup = new Swup({
	containers: ['#swup'],
	plugins: [
		new SwupHeadPlugin({
		// Giữ lại các thẻ không nên thay/đụng (ví dụ css, js đã enqueue sẵn)
		persistTags: [
			'link[rel="stylesheet"]',
			'script[data-swup-persist]',
		],
		}),
		new SwupBodyClassPlugin(),
	],
})
