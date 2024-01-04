// Função para transformar uma tabela em um objeto
function tableToObject(table) {
    var data = [];

    // Verifica se a tabela existe
    if (!table) {
        return null;
    }

    table.querySelectorAll('select').forEach(select => {

        if(select.value !== '-') {
            data.push({
                turma_id: select.value,
                sala_id: select.getAttribute('sala_id'),
                dia: select.getAttribute('dia'),
                tempo: select.getAttribute('tempo'),
            });
        }
    })

    return data;
}

function salvarTabela(table, turno) {
    const tableData = tableToObject(table);
    console.log(tableData)

    fetch('/salvar-tabela', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            tableData,
            turno,
            periodoLetivoId: document.querySelector('select[name="periodoLetivo"]').value,
            salaId: table.getAttribute('data-sala-id')
        })
    }).then(() => {
        alert('Salvo com sucesso!');
    })
}

function tabelaCliqueHandler(target) {
    // deal with progress bar
    const progresses = document.querySelectorAll('.progress');

    for(const progress of progresses) {
        const progressBar = progress.children[0];

        const turmaId = progressBar.getAttribute('turma_id');
        const totalTempos = progressBar.getAttribute('max_tempos');
        
        const selects = document.querySelectorAll('.turma-select');
        const count = Array.from(selects).filter(select => select.selectedOptions[0].value == turmaId).length
        
        console.log(count, totalTempos)

        progressBar.style.width = JSON.stringify((count / totalTempos) * 100) + '%'; 
        document.getElementById(`${turmaId}_progress_texto`).innerHTML = `${count} de ${totalTempos} horas/aula`;

        if(count >= totalTempos) {
            document.querySelectorAll(`.turma-select option[value="${turmaId}"]`).forEach(option => {
                option.disabled = true;
                option.className = 'text-danger'
            });
        } else {
            document.querySelectorAll(`.turma-select option[value="${turmaId}"]`).forEach(option => {
                option.disabled = false;
                option.className = ''
            });
        }
    }

    // deal with days and times
    if(!target) return;
    const day = target.getAttribute('dia');
    const time = target.getAttribute('tempo');
    const outrasSalas = document.querySelectorAll(`select[dia="${day}"][tempo="${time}"]`);

    for(const sala of outrasSalas) {
        if(sala.getAttribute('sala_id') == target.getAttribute('sala_id')) continue;

        for(targetOption of target.children) {
            if(targetOption.value == sala.value && targetOption.value != '-') {
                targetOption.disabled = true;
                targetOption.className = 'text-danger disabled';
            }
        }
    }
}

function selectTurno(select) {
    const url = new URL(window.location.href)
    if(url.searchParams.get('turno')) {
        url.searchParams.delete('turno')
    }
    url.searchParams.append('turno', select.value)
    
    window.location.href = url.href;
}

function exportarTabela(tabelaId, formato) {
    const selects = $('#' + tabelaId).find('select')
    
    for(let select of selects) {
        select.outerHTML = select.selectedOptions[0].innerText.trim()
    }

    $('#' + tabelaId).DataTable({
        dom: 'Bfrtip',
        searching: false,
        pagination: false,
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4', 
                title: 'Horários',
                customize: function(doc) {
                    // Ajustar width da tabela para 100%
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                    // Adicionar margens acima e abaixo das células
                    const rowCount = doc.content[1].table.body.length;
                    const cellPadding = 10; // 5 pontos de margem

                    for (let i = 0; i < rowCount; i++) {
                        const cellCount = doc.content[1].table.body[i].length;
                        for (let j = 0; j < cellCount; j++) {
                            doc.content[1].table.body[i][j].margin = [0, cellPadding, 0, cellPadding];
                        }
                    }
                }
            },
            {
                extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Horários'
            }
        ]
    })

    console.log(formato)
    document.querySelector('.buttons-' + formato).click();
}

window.onload = e => {
    tabelaCliqueHandler();

    for (let table of document.querySelectorAll('table')) {
        table.addEventListener('click', e => {
            tabelaCliqueHandler(e.target);
        })
    }
}
