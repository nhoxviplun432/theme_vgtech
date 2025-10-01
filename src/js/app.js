import Swup from 'swup';
import SwupHeadPlugin from '@swup/head-plugin';
import SwupBodyClassPlugin from '@swup/body-class-plugin';

export const swup = new Swup({
  containers: ['#swup'],
  plugins: [
    new SwupHeadPlugin({
      persistTags: [
        'link[rel="stylesheet"]',
        'script[data-swup-persist]',
      ],
    }),
    new SwupBodyClassPlugin(),
  ],
});
