
        const params = new URLSearchParams(window.location.search);
        const ra = params.get('ra');
        if (ra) {
            document.getElementById('search').value = ra;
        }

        function deleteRow(button) {
            var row = button.parentNode;
            var ra = row.cells[0].innerText; // Obtém o RA da célula na mesma linha
            if (confirm("Tem certeza que deseja excluir o registro com RA " + ra + "?")) {
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>";
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "ra";
                input.value = ra;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
 