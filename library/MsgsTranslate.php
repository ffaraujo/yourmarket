<?php

class MsgsTranslate {

    public function getTranslation() {
        $portugues = array();

        $portugues['stringEmpty'] = "'%value%' é um campo obrigatório";

        //is empty
        $portugues['isEmpty'] = 'Este campo não pode ser vazio';

        //email address
        $portugues['emailAddressInvalid'] = '"%value%" não é um email válido no formato nome@servidor'; //"'%value%' is not a valid email address in the basic format local-part@hostname"
        $portugues['emailAddressInvalidFormat'] = '"%value%" não é um email válido';
        $portugues['emailAddressInvalidHostname'] = "'%hostname%' não é um servidor válido para o endereço de email '%value%'";
        $portugues['emailAddressInvalidMxRecord'] = "'%hostname%' parece não ter um registro MX válido para o endereço de email '%value%'";
        $portugues['emailAddressDotAtom'] = "'%localPart%' não combina com o formato dot-atom";
        $portugues['emailAddressQuotedString'] = "'%localPart%' não combina com o formato quoted-string";
        $portugues['emailAddressInvalidLocalPart'] = "'%localPart%' não é um identificador válido para o endereço de email '%value%'";
        $portugues['emailAddressLengthExceeded'] = "'%value%' excede o tamanho permitido";

        //String Length
        $portugues['stringLengthTooShort'] = "'%value%' é menor que %min% caracteres";
        $portugues['stringLengthTooLong'] = "'%value%' é maior que %max% caracteres";

        //validate digits
        $portugues['notDigits'] = "'%value%' não contém somente dígitos";

        //validate alnum
        $portugues['notAlnum'] = "'%value%' não contém somente caracteres alfa-numéricos";

        //validate alpha
        $portugues['notAlpha'] = "'%value%' não contém somente caracteres alfabéticos";

        //validate between
        $portugues['notBetween'] = "'%value%' não está entre '%min%' e '%max%', inclusivamente";
        $portugues['notBetweenStrict'] = "'%value%' não está estritamente entre '%min%' e '%max%'";

        //validate CCnum
        $portugues['ccnumLength'] = "'%value%' deve conter entre 13 e 19 dígitos";
        $portugues['ccnumChecksum'] = "Algoritmo Luhn (mod-10 checksum) falhou em '%value%'";

        //validate Date
        $portugues['dateNotYYYY-MM-DD'] = "'%value%' não está no formato YYYY-MM-DD";
        $portugues['dateInvalid'] = "'%value%' não é uma data válida";
        $portugues['dateFalseFormat'] = "'%value%' não combina com o formato de data";

        //validate DB
        $portugues['noRecordFound'] = "O registro '%value%' não existe";
        $portugues['recordFound'] = "O registro '%value%' já existe";

        //validate float
        $portugues['notFloat'] = "'%value%' não é um float";

        //validate greater
        $portugues['notGreaterThan'] = "'%value%' não é maior que '%min%'";

        //validate Hex
        $portugues['notHex'] = "'%value%' não contém apenas dígitos hexadecimais";

        //validate hostname
        $portugues['hostnameIpAddressNotAllowed'] = "'%value%' parece ser um endereço IP, mas endereços IP não são permitidos";
        $portugues['hostnameUnknownTld'] = "'%value%' parece ser um servidor de DNS válido mas não combina com a lista TLD conhecida";
        $portugues['hostnameDashCharacter'] = "'%value%' parece ser um servidor de DNS válido mas contém um dash (-) numa posição inválida";
        $portugues['hostnameInvalidHostnameSchema'] = "'%value%' parece ser um servidor de DNS válido mas não combina com o esquema de servidor para o TLD '%tld%'";
        $portugues['hostnameUndecipherableTld'] = "'%value%' parece ser um servidor de DNS válido mas não pode extrair a parte TLD";
        $portugues['hostnameInvalidHostname'] = "'%value%' não combina com a estrutura esperada para um servidor de DNS válido";
        $portugues['hostnameInvalidLocalName'] = "'%value%' não é um nome de rede local válido";
        $portugues['hostnameLocalNameNotAllowed'] = "'%value%' parece ser um nome de rede local válido mas nomes de redes locais não são permitidas";

        //validade identical
        $portugues['notSame'] = 'Tokens não combinam';
        $portugues['missingToken'] = 'Nenhum token foi fornecido';

        //validade IN ARRAY
        $portugues['notInArray'] = "'%value%' não foi encontrado no array";

        //validade int
        $portugues['notInt'] = "'%value%' não é um inteiro";

        //validade ip
        $portugues['notIpAddress'] = "'%value%' não é um endereço IP válido";

        //validate less than
        $portugues['notLessThan'] = "'%value%' não é menor que '%max%'";

        //validate regex
        $portugues['regexNotMatch'] = "'%value%' contém caracteres inválidos";

        //validate captcha
        $portugues ['missingValue'] = 'Campo vazio';
        $portugues ['badCaptcha'] = 'Texto digitado errado';
        $portugues ['missingID'] = 'ID não identificado';

        //extension file
        $portugues['fileExtensionFalse'] = "O arquivo '%value%' não é válido";
        $portugues['fileExtensionNotFound'] = "O arquivo não contém extensão válida";

        //file extension exclude
        $portugues['fileExcludeExtensionFalse'] = "O arquivo '%value%' tem uma extensão inválida";
        $portugues['fileExcludeExtensionNotFound'] = "O arquivo '%value%' não foi encontrado";

        //file exclude Crc32
        $portugues['fileCrc32DoesNotMatch'] = "O arquivo '%value%' não combina com o hash crc32 obtido";
        $portugues['fileCrc32NotDetected'] = "Nenhum hash crc32 foi detectado para o arquivo";
        $portugues['fileCrc32NotFound'] = "O arquivo '%value%' não foi encontrado";

        //file hash
        $portugues['fileHashDoesNotMatch'] = "O arquivo '%value%' não combina com o hash obtido";
        $portugues['fileHashHashNotDetected'] = "Nenhum hash foi detectado para o arquivo";
        $portugues['fileHashNotFound'] = "O arquivo '%value%' não foi encontrado";

        //file is compressed
        $portugues['fileIsCompressedFalseType'] = "O arquivo '%value%' não é comprimido, '%type%' detectado";
        $portugues['fileIsCompressedNotDetected'] = "O mimetype do arquivo '%value%' não foi detectado";
        $portugues['fileIsCompressedNotReadable'] = "O arquivo '%value%' não pôde ser lido";

        //file MD5
        $portugues['fileMd5DoesNotMatch'] = "O arquivo '%value%' não combina com o hash md5 obtido";
        $portugues['fileMd5NotDetected'] = "Nenhum hash md5 foi detectado para o arquivo";
        $portugues['fileMd5NotFound'] = "O arquivo '%value%' não foi encontrado";

        //file mime type
        $portugues['fileMimeTypeFalse'] = "O arquivo '%value%' tem um mimetype falso de '%type%'";
        $portugues['fileMimeTypeNotDetected'] = "O mimetype do arquivo '%value%' não pôde ser detectado";
        $portugues['fileMimeTypeNotReadable'] = "O arquivo '%value%' não pôde ser lido";

        //file sha1
        $portugues['fileSha1DoesNotMatch'] = "O arquivo '%value%' não combina com o hash sha1 obtido";
        $portugues['fileSha1NotDetected'] = "Nenhum hash sha1 foi detectado para o arquivo";
        $portugues['fileSha1NotFound'] = "O arquivo '%value%' não foi encontrado";

        //file size
        $portugues['fileSizeTooBig'] = "Tamanho máximo permitido para o arquivo '%value%' é '%max%' mas '%size%' foi detectado";
        $portugues['fileSizeTooSmall'] = "Tamanho mínimo esperado para o arquivo '%value%' é '%min%' mas '%size%' foi detectado";
        $portugues['fileSizeNotFound'] = "O arquivo '%value%' não foi encontrado";

        //file set size
        $portugues['fileFilesSizeTooBig'] = "A soma de todos os arquivos deve ter o tamanho máximo de '%max%' mas '%size%' foi detectado";
        $portugues['fileFilesSizeTooSmall'] = "A soma de todos os arquivos deve ter o tamanho mínimo de '%min%' mas '%size%' foi detectado";
        $portugues['fileFilesSizeNotReadable'] = "Um ou mais arquivos não puderam ser lidos";

        //file image dimensions
        $portugues['fileImageSizeWidthTooBig'] = "Largura máxima permitida para a imagem '%value%' deve ser '%maxwidth%' mas '%width%' foi detectado";
        $portugues['fileImageSizeWidthTooSmall'] = "Largura mínima esperada para a imagem '%value%' deve ser '%minwidth%' mas '%width%' foi detectado";
        $portugues['fileImageSizeHeightTooBig'] = "Altura máxima permitida para a imagem '%value%' deve ser '%maxheight%' mas '%height%' foi detectado";
        $portugues['fileImageSizeHeightTooSmall'] = "Altura mínima esperada para a imagem '%value%' deve ser '%minheight%' mas '%height%' foi detectado";
        $portugues['fileImageSizeNotDetected'] = "O tamanho da imagem '%value%' não foi detectado";
        $portugues['fileImageSizeNotReadable'] = "A imagem '%value%' não pode ser lida";

        //file upload
        $portugues['fileUploadErrorIniSize'] = "O arquivo '%value%' excede o tamanho definido no ini";
        $portugues['fileUploadErrorFormSize'] = "O arquivo '%value%' excede o tamanho definido no form";
        $portugues['fileUploadErrorPartial'] = "O arquivo '%value%' foi apenas parcialmente carregado";
        $portugues['fileUploadErrorNoFile'] = "O arquivo '%value%' não foi carregado";
        $portugues['fileUploadErrorNoTmpDir'] = "Nenhum diretório temporário foi encontrado para o arquivo '%value%'";
        $portugues['fileUploadErrorCantWrite'] = "O arquivo '%value%' não pode ser escrito";
        $portugues['fileUploadErrorExtension'] = "A extensão retornou um erro enquanto carregava o arquivo '%value%'";
        $portugues['fileUploadErrorAttack'] = "O arquivo '%value%' foi ilegalmente carregado, possível ataque";
        $portugues['fileUploadErrorFileNotFound'] = "O arquivo '%value%' não foi encontrado";
        $portugues['fileUploadErrorUnknown'] = "Erro desconhecido enquanto carregava o arquivo '%value%'";

        //file is image
        $portugues['fileIsImageFalseType'] = "O arquivo '%value%' não é uma imagem, '%type%' detectado";
        $portugues['fileIsImageNotDetected'] = "O mimetype do arquivo '%value%' não foi detectado";
        $portugues['fileIsImageNotReadable'] = "O arquivo '%value%' não pode ser lida";

        //file count
        $portugues['fileCountTooMuch'] = "Excesso de arquivos, máximo de '%max%' é permitido, mas '%count%' foram detectados";
        $portugues['fileCountTooLess'] = "Ausência de arquivos, mínimo de '%min%' era esperado, mas '%count%' foram fornecidos";

        //file not exists
        $portugues['fileExistsDoesNotExist'] = "O arquivo '%value%' não existe";

        //file exists
        $portugues['fileNotExistsDoesExist'] = "O arquivo '%value%' existe";

        
        return $portugues;
    }

}
