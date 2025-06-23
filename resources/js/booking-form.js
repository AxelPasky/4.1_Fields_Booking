export function initBookingForm() {
    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');

    // Se gli elementi non esistono in questa pagina, non fare nulla.
    if (!startTimeSelect || !endTimeSelect) {
        return;
    }

    function updateEndTimeOptions() {
        const selectedStartTime = startTimeSelect.value;

        if (!selectedStartTime) {
            return;
        }

        let isCurrentEndTimeValid = false;

        for (const option of endTimeSelect.options) {
            if (option.value === "") continue;

            if (option.value <= selectedStartTime) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }

            if (option.selected && !option.disabled) {
                isCurrentEndTimeValid = true;
            }
        }

        if (!isCurrentEndTimeValid) {
            endTimeSelect.value = "";
        }
    }

    startTimeSelect.addEventListener('change', updateEndTimeOptions);
    updateEndTimeOptions(); // Esegui al caricamento per lo stato iniziale
}