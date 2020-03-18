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

        frame.setAttribute('data-resized', true);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const frames = document.querySelectorAll(
        'iframe[data-embeddable-autosize]'
    );

    [].forEach.call(frames, frame => {
        if (
            frame.hasAttribute('data-loaded') &&
            !frame.hasAttribute('data-resized')
        ) {
            resizeFrame(frame);
        }
    });
});

window.wpEmbeddableResizeFrame = resizeFrame;
