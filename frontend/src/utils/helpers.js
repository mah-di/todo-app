function handleValidationErrorToast(res, toast) {
    if (!res?.data) {
        toast.error(res.message)
        return
    }

    for (const field in res.data) {
        toast.error(res.data[field].join("\n"))
    }
}

export { handleValidationErrorToast }
