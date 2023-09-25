// Import Vue FilePond
import vueFilePond from 'vue-filepond';

// Import FilePond styles
import "filepond/dist/filepond.min.css";
// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

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

export const removeFilePond = (fileUrl) => {
    let data = {
        fileUrl: fileUrl,
    }

    return axios.delete('/upload-image', { data: data })
}

export const setupFilePond = () => {
    return vueFilePond(
          FilePondPluginFileValidateType,
          FilePondPluginImagePreview
    )
}
