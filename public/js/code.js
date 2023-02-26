function uploadMultipleImage({form, output, attributes = {className: '', name: '', multiple: false, accept: ''}, position}) {
    var form = document.querySelector(form);
    var output = document.querySelector(output);

    form.onchange = function (event) {
        // Disabled and hide current input.
        event.target.setAttribute("hidden", "");
        // Add new input with custom position to do another upload.
        let positions = ['beforebegin', 'afterbegin', 'beforeend', 'afterend'];
        if(positions.includes(position)) {
            let input = `<input type="file" class="${attributes.className}" name="${attributes.name}" multiple="${attributes.multiple}" accept="${attributes.accept}" />`;
            form.insertAdjacentHTML(position, input);
        }
        /* Adds Element AFTER NeighborElement */
        else {
            let input = document.createElement("input");
            Object.assign(input, {
                type: "file",
                className: attributes.className,
                name: attributes.name,
                multiple: attributes.multiple,
                accept: attributes.accept
            });
            position.parentNode.insertBefore(input, position.nextSibling);
        }
        //Preview each image
        for (const file of event.target.files) {
            const div = document.createElement("div");
            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            div.appendChild(img);
            img.onload = event => {
                output.append(div);
                URL.revokeObjectURL(event.target.src);
            };
        }
    }
}
