class CustomTooltip {
    init(params) {
        const eGui = (this.eGui = document.createElement('div'));
        const color = params.color || 'white';
        const data = params.api.getDisplayedRowAtIndex(params.rowIndex).data;

        eGui.classList.add('custom-tooltip');
        eGui.style['background-color'] = color;
        eGui.innerHTML = `
            <p>
                <span>${data.name}</span>
            </p>
            <p>
                <span>Size: </span>
                ${data.size}
            </p>
            <p>
                <span>Organizer: </span>
                ${data.organizer}
            </p>
            <p>
                <span>Template: </span>
                ${data.template_name}
            </p>
            <p>
                <span>Type: </span>
                ${data.event_type}
            </p>
            <p>
                <span>Start date: </span>
                ${data.event_start_date}
            </p>
            <p>
                <span>End date: </span>
                ${data.event_end_date}
            </p>
            <p>
                <span>Accreditation start: </span>
                ${data.accreditation_start_date}
            </p>
            <p>
                <span>Accreditation end: </span>
                ${data.accreditation_end_date}
            </p>
        `;
    }

    getGui() {
        return this.eGui;
    }
}
