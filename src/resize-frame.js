function resizeFrame(frame) {
    if (frame) {
        const {
            scrollHeight,
            offsetHeight,
        } = frame.contentWindow.document.documentElement;
        const localHeight = frame.offsetHeight;

        // Handle the case where the local `<iframe>` (with the default height perhaps)
        // is taller than the content inside
        if (scrollHeight === localHeight && offsetHeight < scrollHeight) {
            frame.style.height = offsetHeight + 'px';
        } else {
            frame.style.height = scrollHeight + 'px';
        }

        frame.style.width = '100%';
    }
}

window.wpEmbeddableResizeFrame = resizeFrame;
