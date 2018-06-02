# naofo.de-lite

> A lite alternative to [naofo.de](https://github.com/naofode/naofo.de)

## Server dependencies
* PHP

## requirements
* Google ReCaptcha v2
* Google Firebase

## Build dependencies
* node.js
* npm or yarn

## Build Setup

1. Rename `src/config/params.js.example` to `src/config/params.js`

2. Edit the `src/config/params.js` with the requested data

3. Build

``` bash
# Install dependencies
npm install

# Build for production with minification
npm run build
```

The result will be in the `dist/` folder, take the content of this folder and upload to php server.
