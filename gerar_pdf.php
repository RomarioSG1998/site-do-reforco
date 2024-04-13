<?php
// Importa a biblioteca TCPDF
require_once('tcpdf/tcpdf.php');

// Função para gerar o PDF
function generatePDF() {
    // Instancia o TCPDF com orientação horizontal
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Define informações do documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Author');
    $pdf->SetTitle('Relatório de Alunos');
    $pdf->SetSubject('Relatório de Alunos');
    $pdf->SetKeywords('TCPDF, PDF, table');

    // Define cabeçalho e rodapé
    $pdf->SetHeaderData('', 0, '', '');

    // Adiciona uma nova página
    $pdf->AddPage();

    // Define fontes e tamanho da fonte menor
    $pdf->SetFont('helvetica', '', 10);

    // Adiciona espaço em branco antes da legenda
    $pdf->Ln(10);

    // Adiciona legenda explicando a cor vermelha
    $pdf->SetFont('', 'B');
    $pdf->SetTextColor(0, 0, 0); // Define a cor do texto como preta
    $pdf->Write(10, 'LEGENDA');
    $pdf->Ln();
    $pdf->Write(10, 'Ativo: ');
    $pdf->SetFont('');
    $pdf->Write(10, 'Aluno que frequenta');
    $pdf->Ln();
    $pdf->SetFont('', 'B');
    $pdf->Write(10, 'Inativo: ');
    $pdf->SetFont('');
    $pdf->Write(10, 'Aluno que não frequenta');
    $pdf->Ln();
    $pdf->Ln();

    // Adiciona espaço em branco entre a legenda e o total de alunos
    $pdf->Ln(10);

    // Inicializa os contadores para alunos ativos e inativos
    $ativos = 0;
    $inativos = 0;

    // Centraliza a logo acima da tabela
    $image_file = './imagens/logo-sem-fundo3.jpg';

    // Verifica se a imagem existe
    if (!file_exists($image_file)) {
        die('Erro: A imagem da logo não foi encontrada.');
    }

    // Obtém a largura da página
    $pageWidth = $pdf->getPageWidth();

    // Define o tamanho e a posição da imagem (centralizada horizontalmente e descendo um pouco após a legenda)
    $imageWidth = 20; // Novo tamanho da imagem
    $imageX = ($pageWidth - $imageWidth) / 2; // Posição X centralizada
    $imageY = $pdf->getY() + 10; // Posição Y ajustada para descer um pouco após a legenda
    $pdf->Image($image_file, $imageX, $imageY, $imageWidth, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    // Adiciona espaço em branco entre a imagem e a tabela
    $pdf->Ln(50); // Altura do espaço em branco em unidades do TCPDF

    // Conexão com o banco de dados
    $hostname = "localhost";
    $bancodedados = "if0_36181052_sistemadoreforco";
    $usuario = "if0_36181052";
    $senha = "A7E5zgIppyr";
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    }

    // Consulta SQL
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sql = "SELECT * FROM alunos WHERE ra LIKE '%$search%' OR nome LIKE '%$search%'";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        // Define HTML para a tabela
        $html = '<table border="1" style="border-collapse: collapse; width: 100%; color: black; background-color: white; border-radius: 20px;">';
        $html .= '<tr style="background-color: #6A5ACD; text-align:center; font-weight: bold;">'; // Destaque para os títulos
        $html .= '<th style="padding: 14px;">RA</th>';
        $html .= '<th style="padding: 14px;">Nome</th>';
        $html .= '<th style="padding: 14px;">Data de Nascimento</th>';
        $html .= '<th style="padding: 14px;">Celular</th>';
        $html .= '<th style="padding: 14px;">Responsável</th>';
        $html .= '<th style="padding: 14px;">Gênero</th>';
        $html .= '<th style="padding: 14px;">Turma</th>';
        $html .= '<th style="padding: 14px;">Situação</th>';
        $html .= '</tr>';

        // Exibe os dados da tabela e incrementa os contadores de alunos ativos e inativos
        while($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["ra"].'</td>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["nome"].'</td>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["datanasc"].'</td>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["celular"].'</td>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["responsavel"].'</td>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["genero"].'</td>';
            $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["turma"].'</td>';
            if ($row["situacao"] == 'Inativo') {
                $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px; color: red; font-weight: bold;">'.$row["situacao"].'</td>';
                $inativos++;
            } else {
                $html .= '<td style="text-align:center; vertical-align: middle; padding: 8px;">'.$row["situacao"].'</td>';
                $ativos++;
            }
            $html .= '</tr>';
        }

        $html .= '</table>';

        // Escreve o HTML no PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Mostra o total de alunos ativos e inativos após a tabela
        $pdf->Ln(10);
        $pdf->SetFont('', 'B');
        $pdf->Write(10, 'Total de Alunos Ativos: ' . $ativos);
        $pdf->Ln();
        $pdf->Write(10, 'Total de Alunos Inativos: ' . $inativos);
    } else {
        // Se não houver resultados, exibe uma mensagem
        $pdf->Cell(0, 10, '0 resultados', 0, 1, 'C');
    }

    $conexao->close();

    // Define o nome do arquivo
    $filename = 'alunos.pdf';

    // Salva o PDF no diretório temporário
    $pdf->Output($filename, 'I');
}

// Chama a função para gerar o PDF
generatePDF();
?>
