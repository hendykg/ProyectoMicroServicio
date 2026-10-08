const express = require('express');
const router = express.Router();
const partRequestController = require('../controllers/partRequest.controller');
const requireAuth = require('../middlewares/auth.middleware');
const upload = require('../middlewares/upload.middleware');

router.post('/', requireAuth, upload, partRequestController.create);
router.get('/', partRequestController.getAll);
router.get('/:id', partRequestController.getById);
router.patch('/:id/status', requireAuth, partRequestController.updateStatus);

module.exports = router;