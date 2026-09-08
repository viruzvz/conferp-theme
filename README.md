# CONFERP Theme

Tema WordPress institucional desenvolvido para o **CONFERP — Conselho Federal de Profissionais de Relações Públicas**.

O projeto foi concebido como uma estrutura própria, modular e escalável para o novo portal institucional do CONFERP, permitindo também o reaproveitamento e a adaptação da arquitetura para os Conselhos Regionais de Profissionais de Relações Públicas — CONRERPs.

O desenvolvimento e a arquitetura inicial do projeto são de responsabilidade de **Carlos Eduardo Gomes Neri**.

---

## Sobre o projeto

O `conferp-theme` é um tema WordPress desenvolvido especificamente para a nova plataforma institucional do CONFERP.

A arquitetura do projeto separa a estrutura global do portal do conteúdo editorial.

Elementos estruturais e institucionais, como:

* Utility Bar;
* Header;
* navegação;
* elementos globais de interface;
* Footer;
* Subfooter;
* componentes compartilhados;
* recursos de acessibilidade;
* estilos globais;

são controlados pelo tema.

O conteúdo e o miolo das páginas podem ser administrados através do WordPress e do **Elementor Pro**, permitindo maior liberdade editorial sem comprometer a estrutura global e a identidade institucional do portal.

---

## Tecnologias

O projeto utiliza principalmente:

* WordPress
* PHP
* HTML5
* CSS3
* SCSS / Sass
* JavaScript
* Bootstrap 5
* Elementor Pro
* Node.js
* npm

### Versões do ambiente de desenvolvimento

| Tecnologia | Versão                                    |
| ---------- | ----------------------------------------- |
| Node.js    | `22.23.2`                                 |
| Sass       | `1.104.0`                                 |
| Chokidar   | `4.0.3`                                   |
| Bootstrap  | `5.3.8`                                   |
| WordPress  | versão estável utilizada pelo ambiente    |
| PHP        | conforme requisitos do ambiente WordPress |

O projeto utiliza **Volta** para fixar a versão do Node.js.

---

# Requisitos

Antes de iniciar o desenvolvimento, tenha instalado:

* Git
* Node.js
* npm
* WordPress
* PHP
* servidor local compatível com WordPress

Pode ser utilizado, por exemplo:

* LocalWP;
* Docker;
* XAMPP;
* Laragon;
* outro ambiente WordPress compatível.

---

# Clonando o projeto

Acesse a pasta de temas da instalação WordPress:

```bash
cd wp-content/themes
```

Clone o repositório:

```bash
git clone https://github.com/viruzvz/conferp-theme.git
```

Entre na pasta:

```bash
cd conferp-theme
```

---

# Instalação das dependências

Execute:

```bash
npm install
```

Preferencialmente, quando estiver trabalhando a partir de um `package-lock.json` já consolidado, utilize:

```bash
npm ci
```

O `npm ci` instala exatamente as versões registradas no `package-lock.json`, sendo recomendado para manter o ambiente consistente entre os desenvolvedores.

---

# Node.js

A versão utilizada pelo projeto é:

```text
Node.js 22.23.2
```

O `package.json` utiliza Volta para fixar essa versão:

```json
"volta": {
  "node": "22.23.2"
}
```

Caso utilize Volta, ao entrar no projeto a versão correta do Node será selecionada automaticamente.

Para verificar:

```bash
node -v
```

Resultado esperado:

```text
v22.23.2
```

---

# SCSS / Sass

Os estilos do tema são desenvolvidos utilizando **SCSS**.

Arquivo principal:

```text
assets/scss/theme.scss
```

O CSS compilado é gerado em:

```text
assets/css/theme.css
```

O compilador utilizado pelo projeto é:

```text
Sass 1.104.0
```

---

# Desenvolvimento

Para acompanhar alterações nos arquivos SCSS durante o desenvolvimento:

```bash
npm run watch
```

O Sass ficará observando:

```text
assets/scss/theme.scss
```

e compilando automaticamente para:

```text
assets/css/theme.css
```

com source map durante o ambiente de desenvolvimento.

---

# Build

Para gerar o CSS de produção:

```bash
npm run build
```

Esse comando gera o CSS:

* minificado;
* sem source map;
* pronto para utilização em produção.

Arquivo gerado:

```text
assets/css/theme.css
```

---

# Scripts npm

Os scripts atualmente utilizados pelo projeto são:

```json
{
  "watch": "sass --watch assets/scss/theme.scss:assets/css/theme.css --style=expanded --source-map",
  "build": "sass assets/scss/theme.scss:assets/css/theme.css --style=compressed --no-source-map"
}
```

### Desenvolvimento

```bash
npm run watch
```

### Produção

```bash
npm run build
```

---

# Bootstrap

O projeto utiliza:

```text
Bootstrap 5.3.8
```

O Bootstrap é utilizado como base para:

* grid;
* containers;
* responsividade;
* navegação;
* componentes estruturais;
* utilities;
* comportamento responsivo.

Componentes específicos do CONFERP devem, sempre que possível, ser desenvolvidos sobre a arquitetura existente sem sobrescrever desnecessariamente o comportamento padrão do Bootstrap.

---

# Estrutura SCSS

O SCSS deve permanecer modular.

O arquivo:

```text
assets/scss/theme.scss
```

é o ponto principal de entrada da compilação.

Novos componentes não devem ser desenvolvidos diretamente no arquivo CSS compilado.

Utilize arquivos SCSS específicos para separar responsabilidades, por exemplo:

```text
assets/scss/
├── theme.scss
├── abstracts/
├── base/
├── components/
├── layout/
├── pages/
└── utilities/
```

A estrutura poderá evoluir conforme o desenvolvimento do tema.

---

# Não editar theme.css diretamente

O arquivo:

```text
assets/css/theme.css
```

é resultado da compilação do SCSS.

Portanto:

**não faça alterações diretamente nesse arquivo.**

Toda alteração de estilo deve ser feita dentro de:

```text
assets/scss/
```

Depois execute:

```bash
npm run watch
```

ou:

```bash
npm run build
```

---

# WordPress

O projeto é um **tema próprio WordPress**.

A estrutura PHP do tema é responsável principalmente por:

* configuração do tema;
* carregamento de assets;
* menus;
* headers;
* footers;
* templates;
* componentes globais;
* Custom Post Types quando aplicável;
* filtros;
* integração com recursos administrativos;
* integrações necessárias ao portal;
* suporte ao Elementor Pro.

Antes de criar uma nova funcionalidade, verifique se ela pertence:

1. ao tema;
2. a um componente;
3. ao WordPress;
4. ao Elementor Pro;
5. ou a um plugin específico.

Isso evita duplicação de responsabilidades.

---

# Elementor Pro

O Elementor Pro é utilizado principalmente para construção e gerenciamento do conteúdo interno das páginas.

A estrutura institucional global permanece no tema.

Isso significa que elementos como Header, Utility Bar, Footer, Subfooter e demais componentes globais não devem ser duplicados dentro das páginas do Elementor.

---

# Git Workflow

A branch principal do projeto é:

```text
main
```

Não é recomendado desenvolver novas funcionalidades diretamente na `main`.

Para uma nova funcionalidade:

```bash
git checkout main
git pull origin main
git checkout -b feature/nome-da-funcionalidade
```

Exemplo:

```bash
git checkout -b feature/header
```

ou:

```bash
git checkout -b feature/transparencia
```

Correções podem utilizar:

```text
fix/
```

Exemplo:

```bash
git checkout -b fix/menu-mobile
```

---

# Commits

Procure utilizar commits pequenos e descritivos.

Exemplos:

```text
feat: cria estrutura do header institucional
```

```text
feat: adiciona utility bar
```

```text
fix: corrige comportamento do menu mobile
```

```text
style: ajusta espaçamento do footer
```

```text
refactor: reorganiza estrutura SCSS do header
```

```text
docs: atualiza documentação do projeto
```

Evite mensagens genéricas como:

```text
alterações
```

```text
ajustes
```

```text
teste
```

---

# Antes de realizar um commit

Verifique o status:

```bash
git status
```

Gere o CSS:

```bash
npm run build
```

Confira novamente:

```bash
git status
```

Depois:

```bash
git add .
git commit -m "descrição da alteração"
```

---

# Atualizando sua branch

Antes de iniciar um novo ciclo de trabalho:

```bash
git checkout main
git pull origin main
```

Depois volte ou crie sua branch de desenvolvimento.

---

# Arquivos que não devem ser versionados

O `.gitignore` do projeto exclui, entre outros:

* `node_modules`;
* arquivos PSD;
* arquivos PSB;
* arquivos PDF;
* arquivos Adobe Illustrator;
* arquivos temporários;
* backups;
* bancos de dados;
* arquivos `.env`;
* logs;
* arquivos de sistema operacional;
* configurações locais de IDE;
* builds de distribuição;
* source maps;
* arquivos compactados.

Arquivos contendo senhas, tokens, chaves de API ou credenciais **nunca devem ser adicionados ao repositório**.

---

# CSS compilado

Apesar de ser um arquivo gerado, o arquivo:

```text
assets/css/theme.css
```

é versionado.

Isso garante que o tema possa ser instalado ou atualizado no WordPress sem exigir Node.js ou Sass no servidor de produção.

Source maps não são versionados.

---

# Fluxo recomendado

Para iniciar o trabalho:

```bash
git pull origin main
npm ci
npm run watch
```

Desenvolva normalmente.

Ao finalizar:

```bash
npm run build
git status
git add .
git commit -m "feat: descrição da alteração"
git push
```

---

# Responsável pelo projeto

**Carlos Eduardo Gomes Neri**

Web Designer / Front-end Developer

Desenvolvimento e arquitetura do tema institucional do CONFERP.

---

## CONFERP

Conselho Federal de Profissionais de Relações Públicas.

Este repositório contém código específico do projeto institucional CONFERP e deve ser utilizado conforme as regras e permissões estabelecidas para o projeto.
