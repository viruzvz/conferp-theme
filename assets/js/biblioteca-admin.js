/**
 * CONFERP Biblioteca Admin
 *
 * Handles the Biblioteca document Media Library selector.
 */

document.addEventListener('DOMContentLoaded', () => {

	const documentBox = document.querySelector(
		'[data-biblioteca-document]'
	);

	if (!documentBox || typeof wp === 'undefined' || !wp.media) {
		return;
	}


	const documentId = documentBox.querySelector(
		'[data-biblioteca-document-id]'
	);

	const selectedDocument = documentBox.querySelector(
		'[data-biblioteca-document-selected]'
	);

	const documentName = documentBox.querySelector(
		'[data-biblioteca-document-name]'
	);

	const documentLink = documentBox.querySelector(
		'[data-biblioteca-document-link]'
	);

	const selectButton = documentBox.querySelector(
		'[data-biblioteca-document-select]'
	);

	const removeButton = documentBox.querySelector(
		'[data-biblioteca-document-remove]'
	);


	if (
		!documentId ||
		!selectedDocument ||
		!documentName ||
		!documentLink ||
		!selectButton ||
		!removeButton
	) {
		return;
	}


	let mediaFrame = null;


	selectButton.addEventListener('click', (event) => {

		event.preventDefault();


		if (mediaFrame) {
			mediaFrame.open();
			return;
		}


		mediaFrame = wp.media({
			title: 'Selecionar documento da Biblioteca',
			button: {
				text: 'Usar este documento'
			},
			multiple: false
		});


		mediaFrame.on('select', () => {

			const attachment = mediaFrame
				.state()
				.get('selection')
				.first()
				.toJSON();


			documentId.value = attachment.id;

			documentName.textContent =
				attachment.filename || attachment.title;

			documentLink.href = attachment.url;
			documentLink.hidden = false;

			selectedDocument.hidden = false;
			removeButton.hidden = false;

			selectButton.textContent = 'Substituir documento';
		});


		mediaFrame.open();
	});


	removeButton.addEventListener('click', (event) => {

		event.preventDefault();

		documentId.value = '';

		documentName.textContent = '';

		documentLink.removeAttribute('href');
		documentLink.hidden = true;

		selectedDocument.hidden = true;
		removeButton.hidden = true;

		selectButton.textContent = 'Selecionar documento';
	});

});