class CustomTooltip {
    init(params) {
        const eGui = (this.eGui = document.createElement('div'));
        const color = params.color || 'white';
        const data = params.api.getDisplayedRowAtIndex(params.rowIndex).data;
        const is_locked = data.is_locked == 1 ? "Yes" : "No";
        const can_unlock = data.can_unlock == 1 ? "Yes" : "No";

        eGui.classList.add('custom-tooltip');
        eGui.style['background-color'] = color;
        eGui.innerHTML = `
            <p>
                <span class"name">${data.name}</span>
            </p>
            <p>
                <span>locked: </span>
                ${is_locked}
            </p>
            <p>
                <span>Can unlock: </span>
                ${can_unlock}
            </p>
        `;
    }

    getGui() {
        return this.eGui;
    }
}
