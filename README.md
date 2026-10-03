# Airport Info Hub

A WordPress plugin demonstrating React integration in three different ways: server-rendered blocks, React islands, and admin settings.

## Features

- **Flight Board Block** - Server-rendered flight data from Aviation Stack API
- **Wait-Time Map Block** - Interactive Leaflet map with live polling
- **Admin Settings Page** - React-based configuration interface

## Installation

1. Install dependencies:
   ```bash
   npm install
   ```

2. Build the plugin:
   ```bash
   npm run build
   ```

3. Activate the plugin in WordPress

4. Configure your API key at Settings → Airport Info Hub

## Usage

Insert blocks via the Gutenberg editor or use the `[wait_times]` shortcode.

## Development

```bash
npm run start      # Development mode
npm run build      # Production build
npm run lint:js    # Lint code
npm run format     # Format code
```

## Technology

- React 18 + TypeScript
- @wordpress/scripts
- React Query
- Leaflet maps
