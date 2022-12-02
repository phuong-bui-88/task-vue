
export const uploadFilePond = (file, progress, field) => {
    const formData = new FormData();
    formData.append('image', file, file.name);
    formData.append(field.name, field.id);

    // related to aborting the request
    const CancelToken = axios.CancelToken;
    const source = CancelToken.source();

    // the request itself
    return  axios({
        method: 'post',
        url: '/upload-image',
        data: formData,
        cancelToken: source.token,
        onUploadProgress: (e) => {
            // updating progress indicator
            progress(e.lengthComputable, e.loaded, e.total);
        }
    }).then(response => {
        return response.data
    })
}

export const removeFilePond = (file) => {

}
