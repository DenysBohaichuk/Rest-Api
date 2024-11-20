export const handleApiError = (error) => {
    console.error(`Error ${error.code || 500}: ${error.message || 'Unknown error'}`);
    return { code: error.code || 500, message: error.message || 'Unknown error' };
};
