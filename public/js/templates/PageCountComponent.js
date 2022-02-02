class PageCountComponent {
    init(params) {
        this.params = params;
        this.eGui = document.createElement('ppp');

        let span = '<span id="total_count" title="Total count" class="total_count_CS">&nbsp;&nbsp;Total Count</i></span>';
        this.eGui.innerHTML = span;
        this.eGui.style.padding = '5px';
    }

    getGui() {
        return this.eGui;
    }

    destroy() {
        this.eButton.removeEventListener('click', this.buttonListener);
    }
}
