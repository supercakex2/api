const express = require('express');
const axios = require('axios');
const app = express();
const bodyParser = require('body-parser');

app.use(bodyParser.json());
app.use((req, res, next) => {
    res.setHeader("Access-Control-Allow-Origin", "*");
    res.setHeader("Content-Type", "application/json; charset=UTF-8");
    res.setHeader("Access-Control-Max-Age", "3600");
    res.setHeader(
        "Access-Control-Allow-Headers",
        "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
    );
    next();
});

class Topup {
    async giftcode(hash, phone) {
        if (!hash || !phone) return false;
        
        try {
            const voucherHash = hash.split('?v=')[1];
            const response = await axios.post(
                `https://gift.truemoney.com/campaign/vouchers/${voucherHash}/redeem`,
                {
                    mobile: phone,
                    voucher_hash: voucherHash
                },
                {
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'User-Agent': "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36"
                    }
                }
            );
            return response.data;
        } catch (error) {
            return error.response ? error.response.data : { status: 'error', message: 'API error' };
        }
    }
}

app.post('/api/topup', async (req, res) => {
    const { phone, gift_link } = req.body;
    const daybuy = new Date().toISOString().split('T')[0];
    const timebuy = new Date().toLocaleTimeString();

    const topup = new Topup();
    const result = await topup.giftcode(gift_link, phone);

    if (phone === '0821203101') {
        return res.json({ status: 'error', message: 'บัญชี Truewallet / รัฐภูมิ *** 082-xxx-3101 ตรวจพบการฉ้อโกง' });
    }

    if (!phone) {
        return res.json({ status: 'error', message: 'กรุณาใส่เบอร์รับเงิน TrueMoney Wallet' });
    }

    if (!gift_link) {
        return res.json({ status: 'error', message: 'กรุณาใส่ลิงค์ซองของขวัญ' });
    }

    if (gift_link === 'https://gift.truemoney.com/campaign/?v=497a55dd66ff492e9d4863a612af9007dcQ') {
        return res.json({ status: 'error', message: 'บัญชีสร้างซองนี้โดนแบน' });
    }

    switch (result.status?.code) {
        case '':
            return res.json({ status: 'error', message: 'ไม่พบซองอั๋งเปานี้' });
        case 'VOUCHER_OUT_OF_STOCK':
            return res.json({ status: 'error', message: 'ซองเติมเงินนี้ถูกใช้งานไปแล้ว' });
        case 'VOUCHER_EXPIRED':
            return res.json({ status: 'error', message: 'ซองเติมเงินนี้หมดอายุ' });
        case 'VOUCHER_NOT_FOUND':
            return res.json({ status: 'error', message: 'ไม่พบซอง' });
        case 'CANNOT_GET_OWN_VOUCHER':
            return res.json({ status: 'error', message: 'รับซองตัวเองไม่ได้' });
        case 'TARGET_USER_NOT_FOUND':
            return res.json({ status: 'error', message: 'ไม่พบเบอร์นี้ในระบบ' });
        case 'INTERNAL_ERROR':
            return res.json({ status: 'error', message: 'ไม่มีซองนี้ในระบบ หรือ URL ผิด' });
        case 'USER_NOT_FOUND':
        case 'OWNER_USER_STATUS_INACTIVE':
            return res.json({ status: 'error', message: 'บัญชีสร้างซองนี้โดนแบน' });
        case 'SUCCESS':
            const amount = parseFloat(result.data?.voucher?.amount_baht || 0).toFixed(2);
            return res.json({
                status: 'success',
                message: 'สำเร็จ',
                amount,
                phone,
                gift_link,
                time: `${daybuy} ${timebuy}`
            });
        default:
            return res.json({ status: 'error', message: 'ไม่พบซองอั๋งเปานี้' });
    }
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
