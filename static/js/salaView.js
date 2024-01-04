const colors = {
    Segunda: '#ffffff', //generateRandomLightColor(),
    Terca: '#ababab', //generateRandomLightColor(),
    Quarta: '#ffffff', //generateRandomLightColor(),
    Quinta: '#ababab', //generateRandomLightColor(),
    Sexta: '#ffffff', //generateRandomLightColor()
}

function exportarTabelaSalaView(tabelaId, formato) {
    const selects = $(tabelaId).find('select')

    for (let select of selects) {
        select.outerHTML = select.selectedOptions[0].innerText.trim()
    }

    $(tabelaId).DataTable({
        dom: 'Bfrtip',
        searching: false,
        pagination: false,
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Horários',
                customize: function (doc) {
                    doc.styles.tableHeader.fontSize = 8;
                    doc.styles.title.fontSize = 10;

                    // Ajustar width da tabela para 100%
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                    // Adicionar margens acima e abaixo das células
                    const rowCount = doc.content[1].table.body.length;
                    const cellPadding = 2; // 5 pontos de margem

                    // definir cor de fundo das linhas dos dias e do tempo
                    doc.content[1].table.body.forEach((row, i) => {
                        if (i !== 0) {
                            // linha Dia
                            row[0].fillColor = colors[row[0].text];

                            // linha Tempo
                            row[1].fillColor = '#8dbdc9';
                        }
                    })

                    // Adicionar margens acima e abaixo das celulas
                    for (let i = 0; i < rowCount; i++) {
                        const cellCount = doc.content[1].table.body[i].length;

                        for (let j = 0; j < cellCount; j++) {
                            doc.content[1].table.body[i][j].margin = [0, cellPadding, 0, cellPadding];
                        }
                    }

                    const emptyLine = (spaces) => {
                        const line = {text: spaces, style: "tableBodyOdd", fillColor: '#6e6e6e', margin: [0,-2,0,-2]};
                        const lines = [line];
                        
                        for(let i = 0; i < doc.content[1].table.body[0].length - 1; i++) {
                            lines.push({text: "", style: "tableBodyOdd", fillColor: '#6e6e6e', margin: [0,-2,0,-2]});
                        }
                        return lines
                    }
                    
                    // adiciona espaços vazios entre os dias
                    let spaces = '';
                    for(let i = 0; i < 4; i++) {
                        spaces += ' ';
                        const line = emptyLine(spaces);
                        doc.content[1].table.body.push(line);
                    }

                    // Definir a largura das duas primeiras colunas como 20px
                    doc.content[1].table.widths = ['8%', '8%'].concat(new Array(doc.content[1].table.body[0].length - 2).fill('*'));

                    // fazendo sorting na tabela
                    const order = ['Dia', 'Segunda', " ", 'Terca', "  ", 'Quarta', "   ", 'Quinta', "    ", 'Sexta'];
                    doc.content[1].table.body.sort((a, b) => {
                        const indexA = order.indexOf(a[0].text);
                        const indexB = order.indexOf(b[0].text);
                        return indexA - indexB;
                    })

                    // define tamanho da fonte
                    doc.defaultStyle.fontSize = 7;

                    // Diminuir a margem superior do título
                    doc.pageMargins = [20, 10, 20, 20];
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

    document.querySelector('.buttons-' + formato).click();
    // window.location.reload();
}

function generateRandomLightColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';

    // Gerar seis caracteres hexadecimais aleatórios
    for (let i = 0; i < 6; i++) {
        // Obter um caractere aleatório
        const randomIndex = Math.floor(Math.random() * 16);
        const randomChar = letters[randomIndex];
        // Adicionar o caractere à cor
        color += randomChar;
    }

    return color;
}

window.onload = () => {
    const columnNames = $('td[role="title"]');

    for (let columnName of columnNames) {
        columnName.style.backgroundColor = colors[columnName.innerText.trim()];
    }
}