class ShowMoreComponent {
    init(params) {
        this.params = params;

        this.eGui = document.createElement('kkk');

        let button = '<a href="javascript:void(0)" id="filtersButton" style="display:none" title="See more"><i class="fas fa-plus">&nbsp;&nbsp;See more</i></a>';
        this.eGui.innerHTML = button;
        this.eGui.style.padding = '5px';
    }

    getGui() {
        return this.eGui;
    }

    destroy() {
        this.eButton.removeEventListener('click', this.buttonListener);
    }
}
