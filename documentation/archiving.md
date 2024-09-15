# Shaark - Archiving

- [PDF Archiving](#pdf-archiving)
- [Media Archiving](#media-archiving)
- [Caveats](#caveats)

## PDF Archiving

PDF archiving is used to backup your links as a PDF using printing functionnality of Chromium.  
We use [Browsershot](https://github.com/spatie/browsershot) to achieve it.

### Configuration

Once your app is installed, access the **Settings** section, enable **PDF archiving** then configure your **Node path** (defaults to `/usr/bin/node`).
You can use the **Check** button to test your configuration.

## Media Archiving

Media archiving is used to get original media file from Youtube, Soundcloud and [many more](https://github.com/yt-dlp/yt-dlp/blob/master/supportedsites.md) using [yt-dlp](https://github.com/yt-dlp/yt-dlp).

### Installation

You need to install manually [yt-dlp](https://github.com/yt-dlp/yt-dlp#installation) and have access to Python on your system.

### Configuration

Once your app is installed, access the **Settings** section, enable **Media archiving** then configure your **Youtube-dl path** (defaults to `/usr/bin/youtube-dl`) and your **Python path** (defaults to `/usr/bin/python`). 
You can use the **Check** button to test your configuration.

## Caveats

Archiving is performance expensive and should be run in production using queues. See our [installation configuration](https://github.com/MarceauKa/shaark/blob/dev/documentation/installation.md) to learn more about it.
