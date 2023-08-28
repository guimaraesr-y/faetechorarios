let temposProfessoresUl;

// Função para transformar uma tabela em um objeto
function tableToObject(table) {
    var data = [];

    // Verifica se a tabela existe
    if (!table) {
        return null;
    }

    table.querySelectorAll('select').forEach(select => {
        if(parseInt(select.value) !== 0) {
            data.push({
                professor_turma_id: select.value,
                professor_id: select.selectedOptions[0].getAttribute('professor_id'),
                horario_id: select.getAttribute('horario_id'),
                sala_id: select.getAttribute('sala_id'),
                dia: select.getAttribute('dia'),
                horario: select.getAttribute('horario'),
            });
        }
    })

    return data;
}

function salvarTabelas() {
    const tables = [];

    for(let table of document.getElementsByTagName('table')) {
        const tableData = tableToObject(table);
        tables.push(...tableData);
    }

    fetch('/salvar-tabelas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(tables)
    }).then(() => {
        alert('Salvo com sucesso!');
    })
}

function countProfessoresTempos() {
    const contagem = {};

    // faz a contagem dos horarios da turma atual
    document.querySelectorAll('table').forEach(table => {
        const horarioLabs = tableToObject(table);
        horarioLabs.forEach(horario => {
            console.log(horario)
            contagem[horario.professor_id] = (contagem[horario.professor_id] || 0) + 1;
        });
    });

    // faz a contagem do horariosIndisponiveis
    horariosIndisponiveis.forEach(horario => {
        contagem[horario.professor_id] = (contagem[horario.professor_id] || 0) + 1;
    });

    return contagem;
}

function tabelaCliqueHandler() {
    const count = countProfessoresTempos();

    for(const professorId in count) {
        if(professorId == 0) continue;
        const progressBar = document.querySelector(`#prof_${professorId}_progress`);
        const totalTempos = parseInt(progressBar.getAttribute('max_tempos'));

        console.log(count)
        
        progressBar.style.width = JSON.stringify((count[professorId] / totalTempos) * 100) + '%'; // count[professorId];
        document.querySelector(`#prof_${professorId}_texto`).innerHTML = `${count[professorId]} de ${totalTempos} horas/aula`;

        if(count[professorId] >= totalTempos) {
            document.querySelectorAll(`option[value="${professorId}"]`).forEach(option => {
                option.disabled = true;
            });
        }
    }
}

window.onload = e => {
    tabelaCliqueHandler();

    for (let table of document.querySelectorAll('table')) {
        table.addEventListener('click', e => {
            tabelaCliqueHandler();
        })
    }
}
