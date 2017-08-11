# Excel para SQL
## Conversor de tabelas do excel no formato xls ou xlsx para insert em sql

### A biblioteca utilizado é a [PHPExcel](https://github.com/PHPOffice/PHPExcel)
### Requisitos:
> Servidor PHP.

> alterar as configurações do ``php.ini`` caso o arquivo seja muito grande. Os valores abaixo podem variar de acordo com o peso do arquivo a ser convertido.
```sh
upload_max_filesize  128M  
post_max_size        128M  
max_execution_time   300   
max_input_time       300 
```

### Após tudo configurado falta apenas iniciar o servidor:
- abra o cmd e entre no diretorio dos arquivos ``cd "C:\diretório``" 
- execute o comando ``php -S localhost:8080``
- abra o navegador e acesse ``localhost:8080``
- anexe o arquivo a ser convertido
- se desejar remover os espaçamentos no inicio e no fim dos campos da tabela marque a opção ``remover espaçamento``
- defina o nome da tabela do insert.
- pronto ;), agora é só baixar o arquivo ``.sql`` gerado.

![site](https://user-images.githubusercontent.com/21013545/29214273-0d41b774-7e7d-11e7-8ec6-18e6a25ee6d2.PNG)
